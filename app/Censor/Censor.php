<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Censor;

use App\Models\StopWord;
use Discuz\Contracts\Setting\SettingsRepository;
use Discuz\Foundation\Application;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class Censor
{
    /**
     * 忽略、不处理
     */
    const IGNORE = '{IGNORE}';

    /**
     * 审核
     */
    const MOD = '{MOD}';

    /**
     * 禁用
     */
    const BANNED = '{BANNED}';

    /**
     * 替换
     */
    const REPLACE = '{REPLACE}';

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
     * @var
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
     * @param $content
     * @param string $type 'ugc' or 'username'
     * @return string
     */
    public function checkText($content, $type = 'ugc')
    {
        if (!blank($content)) {
            // 设置关闭时，直接返回原内容
            if (!$this->setting->get('censor', 'default', true)) {
                return $content;
            }

            // 本地敏感词校验
            $content = $this->localStopWordsCheck($content, $type);

            // 腾讯云敏感词校验
            if ($this->setting->get('qcloud_cms_text', 'qcloud', false)) {
                // 判断是否大于 5000 字
                if (Str::of($content)->length() > 5000) {
                    $begin = $this->tencentCloudCheck(Str::of($content)->substr(0, 4900));
                    $middle = $this->tencentCloudCheck(Str::of($content)->substr(4900, 5100));
                    $end = $this->tencentCloudCheck(Str::of($content)->substr(5100));

                    $content = $begin . Str::of($middle)->substr(0, 5100 - 4900) . $end;
                } else {
                    $content = $this->tencentCloudCheck($content);
                }
            }
        }

        return $content;
    }

    /**
     * @param $content
     * @param string $type 'ugc' or 'username'
     * @return string
     */
    public function localStopWordsCheck($content, $type)
    {
        // 处理指定类型非忽略的敏感词
        StopWord::when(in_array($type, ['ugc', 'username']), function ($query) use ($type) {
            return $query->where($type, '<>', self::IGNORE);
        })->cursor()->tapEach(function ($word) use (&$content, $type) {
            // 转义元字符并生成正则
            $find = '/' . addcslashes($word->find, '\/^$()[]{}|+?.*') . '/i';

            if ($word->{$type} == self::REPLACE) {
                $content = preg_replace($find, $word->replacement, $content);
            } else {
                if ($word->{$type} == self::MOD) {
                    if (preg_match($find, $content, $matches)) {
                        // 记录触发的审核词
                        array_push($this->wordMod, $word->find);

                        $this->isMod = true;
                    }
                } elseif ($word->{$type} == self::BANNED) {
                    if (preg_match($find, $content, $matches)) {
                        throw new CensorNotPassedException('content_banned');
                    }
                }
            }
        })->each(function ($word) {
            // tapEach 尚未真正开始处理，在此处触发 tapEach
        });

        return $content;
    }

    /**
     * @param $content
     * @return mixed
     */
    public function tencentCloudCheck($content)
    {
        $qcloud = $this->app->make('qcloud');

        /**
         * @property \Discuz\Qcloud\QcloudManage
         */
        $result = $qcloud->service('cms')->TextModeration($content);
        $keyWords = Arr::get($result, 'Data.Keywords', []);

        if (!blank($keyWords)) {
            // 记录触发的审核词
            $this->wordMod = array_merge($this->wordMod, $keyWords);
            $this->isMod = true;
        }

        return $content;
    }

    /**
     * 检测敏感图片
     *
     * @param mixed $filePathname 图片绝对路径
     * @param bool $isRemote 是否是远程图片
     */
    public function checkImage($filePathname, $isRemote = false)
    {
        if ($this->setting->get('qcloud_cms_image', 'qcloud', false)) {
            $qcloud = $this->app->make('qcloud');

            $params = [];

            if ($isRemote) {
                $params['FileUrl'] = $filePathname;
            } else {
                $params['FileContent'] = base64_encode(file_get_contents($filePathname));
            }

            /**
             * TODO: 如果config配置图片不是放在本地这里需要修改base64为 传输 FileUrl地址路径
             * @property \Discuz\Qcloud\QcloudManage
             */
            $result = $qcloud->service('cms')->ImageModeration($params);
            $data = Arr::get($result, 'Data', []);

            if (!empty($data)) {
                $data['EvilType'] != 100 ? $this->isMod = true : $this->isMod = false;
            }
        }
    }

    /**
     * 检验身份证号码和姓名是否真实
     *
     * @param string $identity 身份证号码
     * @param string $realname 姓名
     * @return array
     */
    public function checkReal(string $identity, string $realname)
    {
        $qcloud = $this->app->make('qcloud');
        return $qcloud->service('faceid')->idCardVerification($identity, $realname);
    }
}
