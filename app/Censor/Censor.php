<?php

/**
 * Copyright (C) 2020 Tencent Cloud.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace App\Censor;

use App\Models\Attachment;
use App\Models\StopWord;
use Discuz\Contracts\Setting\SettingsRepository;
use Discuz\Foundation\Application;
use Discuz\Qcloud\QcloudManage;
use Discuz\Wechat\EasyWechatTrait;
use EasyWeChat\Kernel\Exceptions\InvalidConfigException;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class Censor
{
    use EasyWechatTrait;

    /**
     * @var array
     */
    public $allowTypes = ['ugc', 'username', 'signature', 'dialog'];

    /**
     * 是否合法（放入待审核）
     *
     * @var bool
     */
    public $isMod = false;

    /**
     * 触发的替换词
     *
     * @var array
     */
    public $wordReplace = [];

    /**
     * 触发的审核词
     *
     * @var array
     */
    public $wordMod = [];

    /**
     * 触发的禁用词
     *
     * @var array
     */
    public $wordBanned = [];

    /**
     * @var Application
     */
    public $app;

    /**
     * @var SettingsRepository
     */
    public $setting;

    /**
     * @param SettingsRepository $setting
     * @param Application $app
     */
    public function __construct(SettingsRepository $setting, Application $app)
    {
        $this->app = $app;
        $this->setting = $setting;
    }

    /**
     * Check text information.
     *
     * @param string $content
     * @param string $type
     * @return string
     * @throws GuzzleException
     * @throws InvalidConfigException
     */
    public function checkText($content, $type = 'ugc')
    {
        if (blank($content)) {
            return $content;
        }

        // 本地敏感词校验（暂时无此开关，默认开启）
        if ($this->setting->get('censor', 'default', true)) {
            $content = $this->localStopWordsCheck($content, $type);
        }

        /**
         * 腾讯云敏感词校验
         * 小程序敏感词校验
         */
        if ($this->setting->get('qcloud_cms_text', 'qcloud', false)) {
            // 判断是否大于 5000 字
            if (($length = Str::of($content)->length()) > 5000) {
                $content = $this->overrunContent($length, $content);
            } else {
                $content = $this->tencentCloudCheck($content);
            }
        } elseif ($this->setting->get('miniprogram_close', 'wx_miniprogram', false)) {
            $content = $this->miniProgramCheck($content);
        }

        // Delete repeated words
        $this->wordMod = array_unique($this->wordMod);

        return $content;
    }

    /**
     * 循环拆分数字 - 过滤验证
     *
     * @param int $length
     * @param string $content
     * @return string
     */
    public function overrunContent($length, $content)
    {
        $content = Str::of($content);

        // init
        $size = 4900;
        $repeat = 100;

        $array = [];
        for ($i = 0; ; $i++) {
            $start = $i ? $i * ($size - $repeat) : 0;

            if ($start >= $length) {
                break;
            }

            $replaced = $this->tencentCloudCheck($content->substr($start, $size)->__toString());

            $array[] = Str::of($replaced)->substr(0, -$repeat)->__toString();
        }

        return implode('', $array);
    }

    /**
     * @param string $content
     * @param string $type
     * @return string
     */
    public function localStopWordsCheck(string $content, string $type)
    {
        // 处理指定类型非忽略的敏感词
        StopWord::query()
            ->when(in_array($type, $this->allowTypes), function (Builder $query) use ($type) {
                return $query->where($type, '<>', StopWord::IGNORE)->where($type, '<>', '');
            })
            ->cursor()
            ->tapEach(function ($word) use (&$content, $type) {
                // 转义元字符并生成正则
                $find = '/' . addcslashes($word->find, '\/^$()[]{}|+?.*') . '/iu';

                // 将 {n} 转换为 .{0,n}（当 n 为 0 - 99 时）
                $find = preg_replace('/\\\{(\d{1,2})\\\}/', '.{0,${1}}', $find);

                if ($word->{$type} === StopWord::REPLACE) {
                    $content = preg_replace($find, $word->replacement, $content);
                } else {
                    if (preg_match($find, $content, $matches)) {
                        if ($word->{$type} === StopWord::MOD) {
                            // 记录触发的审核词
                            array_push($this->wordMod, head($matches));

                            $this->isMod = true;
                        } elseif ($word->{$type} === StopWord::BANNED) {
                            throw new CensorNotPassedException('content_banned');
                        }
                    }
                }
            })
            ->each(function ($word) {
                // tapEach 尚未真正开始处理，在此处触发 tapEach
                /** @see https://learnku.com/docs/laravel/7.x/collections/7483#4017b9 */
            });

        return $content;
    }

    /**
     * @param string $content
     * @return string
     */
    public function tencentCloudCheck($content)
    {
        $qcloud = $this->app->make('qcloud');

        /**
         * @property QcloudManage
         * @see 文本内容安全文档 https://cloud.tencent.com/document/product/1124/46976
         */
        $result = $qcloud->service('cms')->TextModeration($content);

        $keyWords = Arr::get($result, 'Data.Keywords', []);

        if (isset($result['Data']['DetailResult'])) {
            /**
             * filter 筛选腾讯云敏感词类型范围
             * Normal：正常，Polity：涉政，Porn：色情，Illegal：违法，Abuse：谩骂，Terror：暴恐，Ad：广告，Custom：自定义关键词
             */
            $filter = ['Normal', 'Ad']; // Tag Setting 可以放入配置
            $filtered = collect($result['Data']['DetailResult'])->filter(function ($item) use ($filter) {
                if (in_array($item['EvilLabel'], $filter)) {
                    $item = [];
                }
                return $item;
            });

            $detailResult = $filtered->pluck('Keywords');
            $detailResult = Arr::collapse($detailResult);
            $keyWords = array_merge($keyWords, $detailResult);
        }

        if (!blank($keyWords)) {
            // 记录触发的审核词
            $this->wordMod = array_merge($this->wordMod, $keyWords);
            $this->isMod = true;
        }

        return $content;
    }

    /**
     * @param string $content
     * @return string
     * @throws InvalidConfigException
     * @throws GuzzleException
     */
    public function miniProgramCheck($content)
    {
        $easyWeChat = $this->miniProgram();

        try {
            $result = $easyWeChat->content_security->checkText($content);
        } finally {
            $result = $result ?? [];
        }

        if (Arr::get($result, 'errcode', 0) !== 0) {
            $this->isMod = true;
        }

        return $content;
    }

    /**
     * 检测敏感图片
     *
     * @param string $filePathname 图片绝对路径
     * @param bool $isRemote 是否是远程图片
     * @throws GuzzleException
     * @throws InvalidConfigException
     */
    public function checkImage($filePathname, $isRemote = false)
    {
        if ((bool) $this->setting->get('qcloud_cms_image', 'qcloud', false)) {
            $params = [];

            if ($isRemote) {
                $params['FileUrl'] = $filePathname;
            } else {
                $params['FileContent'] = base64_encode(file_get_contents($filePathname));
            }

            /**
             * TODO: 如果config配置图片不是放在本地这里需要修改base64为 传输 FileUrl地址路径
             * @property QcloudManage
             */
            $result = $this->app->make('qcloud')->service('cms')->ImageModeration($params);
            $data = Arr::get($result, 'Data', []);

            if (!empty($data)) {
                $data['EvilType'] != 100 ? $this->isMod = true : $this->isMod = false;
            }
        } elseif ((bool) $this->setting->get('miniprogram_close', 'wx_miniprogram', false)) {
            $easyWeChat = $this->miniProgram();

            if ($isRemote) {
                $tmpFile = tempnam(storage_path('/tmp'), 'checkImage');

                try {
                    $fileSize = file_put_contents($tmpFile, file_get_contents(
                        $filePathname . (strpos($filePathname, '?') === false ? '?' : '&')
                        . 'imageMogr2/thumbnail/' . Attachment::FIX_WIDTH . 'x' . Attachment::FIX_WIDTH
                    ));

                    $result = $fileSize ? $easyWeChat->content_security->checkImage($tmpFile) : [];
                } finally {
                    @unlink($tmpFile);
                }
            } else {
                try {
                    $result = $easyWeChat->content_security->checkImage($filePathname);
                } finally {
                    $result = $result ?? [];
                }
            }

            if (Arr::get($result, 'errcode', 0) !== 0) {
                $this->isMod = true;
            }
        }
    }

    /**
     * 检验身份证号码和姓名是否真实
     *
     * @param string $identity 身份证号码
     * @param string $realName 姓名
     * @return array
     */
    public function checkReal(string $identity, string $realName)
    {
        $qcloud = $this->app->make('qcloud');
        return $qcloud->service('faceid')->idCardVerification($identity, $realName);
    }
}
