<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Rules\Settings;

use Discuz\Validation\AbstractRule;

class CashSumLimit extends AbstractRule
{
    /**
     * 单日提现总金额 - 验证
     * (不能超过5000)
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return $value > 5000 ? false : true ;
    }

    /**
     * Get the validation error message.
     *
     * @return string|array
     */
    public function message()
    {
        return trans('setting.limit_exceed_5000');
    }
}
