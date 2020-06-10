<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Rules\Settings;

use Discuz\Validation\AbstractRule;

class CashMinSum extends AbstractRule
{
    private $cashMaxSum;

    private $cashSumLimit;

    private $bool = true;

    /**
     * 默认错误提示
     * @var string
     */
    public $message = 'set_error';

    public function __construct($cashMaxSum, $cashSumLimit)
    {
        $this->cashMaxSum = $cashMaxSum;
        $this->cashSumLimit = $cashSumLimit;
    }

    /**
     * 单次提现最小金额 - 验证
     * (不能大于最大金额[cash_max_sum]、不能大于单日提现总金额[cash_sum_limit]、不能超过5000)
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool|void
     */
    public function passes($attribute, $value)
    {
        if ($value > $this->cashMaxSum) {
            $this->bool = false;
            $this->message = 'min_greater_than_max';
        }

        if ($value > $this->cashSumLimit) {
            $this->bool = false;
            $this->message = 'min_greater_than_limit';
        }

        if ($value > 5000) {
            $this->bool = false;
            $this->message = 'min_exceed_5000';
        }

        return $this->bool;
    }

    /**
     * @return array|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Translation\Translator|string|null
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function message()
    {
        return trans('setting.' . $this->message);
    }
}
