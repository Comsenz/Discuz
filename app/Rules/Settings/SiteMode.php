<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Rules\Settings;

use Discuz\Contracts\Setting\SettingsRepository;
use Discuz\Validation\AbstractRule;

/**
 * 站点付费 - 验证
 *
 * Class SiteMode
 * @package App\Rules\Settings
 */
class SiteMode extends AbstractRule
{
    /**
     * 默认错误提示
     * @var string
     */
    public $message = 'set_error';

    /**
     * 判断不允许存在的扩展名
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if ($value == 'pay') {
            // 验证 价格不能为空
            $settings = app()->make(SettingsRepository::class);

            $sitePrice =  $settings->get('site_price', 'default', 0);

            if (empty($sitePrice)) {
                $this->message = 'site_mode_not_found_price';
                return false;
            }
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string|array
     */
    public function message()
    {
        return trans('setting.' . $this->message);
    }
}
