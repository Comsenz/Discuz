<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Rules\Settings;

use Discuz\Validation\AbstractRule;

class CashMaxSum extends AbstractRule
{
    private $cashSumLimit;

    private $bool = true;

    /**
     * 默认错误提示
     * @var string
     */
    public $message = 'set_error';

    public function __construct($cashSumLimit)
    {
        $this->cashSumLimit = $cashSumLimit;
    }

    /**
     * 单次提现最大金额 - 验证
     * (不能大于单日提现总金额[cash_sum_limit]、不能超过5000)
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if ($value > $this->cashSumLimit) {
            $this->bool = false;
            $this->message = 'max_greater_than_limit';
        }

        if ($value > 5000) {
            $this->bool = false;
            $this->message = 'max_exceed_5000';
        }

        return $this->bool;
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
