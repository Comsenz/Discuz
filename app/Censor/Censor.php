<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Censor;

use App\Models\StopWord;
use Discuz\Contracts\Setting\SettingsRepository;

class Censor
{
    /**
     * 替换
     */
    const REPLACE = '{REPLACE}';

    /**
     * 审核
     */
    const MOD = '{MOD}';

    /**
     * 禁用
     */
    const BANNED = '{BANNED}';

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

    public $wordBanned = [];

    public $wordMod = [];

    public $wordReplace = [];

    public function __construct(SettingsRepository $setting)
    {
        $this->isTurnOn = $setting->get('censor', 'default', true) == 'true';
    }

    /**
     * @param $content
     * @param string $type
     * @return string
     */
    public function check($content, $type = 'ugc')
    {
        // 设置关闭时，直接返回原内容
        if (! $this->isTurnOn) {
            return $content;
        }

        // 本地敏感词校验
        $content = $this->localStopWordsCheck($content, $type);

        // 腾讯云敏感词校验
        $content = $this->tencentCloudCheck($content);

        return $content;
    }

    /**
     * @param $content
     * @param $type
     * @return string
     */
    public function localStopWordsCheck($content, $type)
    {
        StopWord::orderBy($type)->cursor()->tapEach(function ($word) use (&$content, $type) {
            $find = '/' . addcslashes($word->find, '/') . '/i';

            if ($word->{$type} == self::REPLACE) {
                $content = preg_replace($find, $word->replacement, $content);
            } else {
                if ($word->{$type} == self::MOD) {
                    if (preg_match($find, $content, $matches)) {
                        $this->isMod = true;
                    }
                } elseif ($word->{$type} == self::BANNED) {
                    if (preg_match($find, $content, $matches)) {
                        throw new CensorNotPassedException('content_banned');
                    }
                }
            }
        })->each(function ($word) {
            // 触发 tapEach
        });

        return $content;
    }

    /**
     * @param $content
     * @return string
     */
    public function tencentCloudCheck($content)
    {
        return $content;
    }
}
