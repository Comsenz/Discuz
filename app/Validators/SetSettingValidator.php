<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Validators;

use App\Rules\Settings\CashMaxSum;
use App\Rules\Settings\CashMinSum;
use App\Rules\Settings\CashSumLimit;
use App\Rules\Settings\QcloudSecretVerify;
use Discuz\Foundation\AbstractValidator;
use Illuminate\Validation\Factory;

class SetSettingValidator extends AbstractValidator
{
    protected $settings;

    public function __construct(Factory $validator)
    {
        parent::__construct($validator);
    }

    protected function getRules()
    {
        $rules = [
            'qcloud_secret_id' => ['filled', new QcloudSecretVerify($this->filterDefault('qcloud_secret_key'))],
            'password_length' => ['gte:0'],
            'cash_interval_time' => ['gte:0'],
            'cash_rate' => ['gte:0', 'max:100'],  // 提现手续费率
            'cash_min_sum' => ['gte:0', new CashMinSum($this->filterDefault('cash_max_sum', 0), $this->filterDefault('cash_sum_limit', 0))],
            'cash_max_sum' => ['gte:0', new CashMaxSum($this->filterDefault('cash_sum_limit', 0))],
            'cash_sum_limit' => ['gte:0', new CashSumLimit()],
        ];

        return $rules;
    }

    /**
     * @return array
     * @property :attribute /resources/lang/zh-CN/validation.php
     */
    protected function getMessages()
    {
        return [
//            'password_length.gte' => '密码长度必须大于或等于:value',
        ];
    }

    /**
     * 未传输的默认值 - 过滤
     *
     * @param $str
     * @param $default
     * @return string|int
     */
    protected function filterDefault($str, $default = '')
    {
        if (!array_key_exists($str, $this->data)) {
            return $default;
        }

        return $this->data[$str];
    }
}
