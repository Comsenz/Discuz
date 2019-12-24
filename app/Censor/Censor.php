<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Censor;

use App\Models\StopWord;
use Discuz\Contracts\Setting\SettingsRepository;
use Discuz\Foundation\Application;

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
     * 开关
     *
     * @var bool
     */
    public $isTurnOn;

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
     * @param SettingsRepository $setting
     */
    public function __construct(SettingsRepository $setting, Application $app)
    {
        $this->isTurnOn = $setting->get('censor', 'default', true) == 'true';
        $this->app = $app;
    }

    /**
     * @param $content
     * @param string $type 'ugc' or 'username'
     * @param bool $onlyMod
     * @return string
     */
    public function check($content, $type = 'ugc', $onlyMod = false)
    {
        // 设置关闭时，直接返回原内容
        if (! $this->isTurnOn) {
            return $content;
        }

        // 本地敏感词校验
        $content = $this->localStopWordsCheck($content, $type, $onlyMod);

        // 腾讯云敏感词校验
        $content = $this->tencentCloudCheck($content);
dd($content);
        return $content;
    }

    /**
     * @param $content
     * @param string $type 'ugc' or 'username'
     * @param bool $onlyMod
     * @return string
     */
    public function localStopWordsCheck($content, $type, $onlyMod = false)
    {
        StopWord::when($onlyMod, function ($query) use ($type) {
            return $query->where($type, self::MOD);
        })->cursor()->tapEach(function ($word) use (&$content, $type) {
            $find = '/' . addcslashes($word->find, '/') . '/i';

            if ($word->{$type} == self::REPLACE) {
                $content = preg_replace($find, $word->replacement, $content);
            } else {
                if ($word->{$type} == self::MOD) {
                    if (preg_match($find, $content, $matches)) {
                        array_push($this->wordMod, $find);

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
     * @return string
     */
    public function tencentCloudCheck($content)
    {
        dump($content);
        $qcloud = $this->app->make('qcloud');
        $result = $qcloud->service('cms')->TextModeration($content);
        dd($result);

        return $content;
    }
}
