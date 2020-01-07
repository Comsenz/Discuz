<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Validators;

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
            'password_length' => 'gte:0',
            'cash_interval_time' => 'gte:0',
            'cash_rate' => 'gte:0',
            'cash_min_sum' => 'gte:0',
            'cash_max_sum' => 'gte:0',
            'cash_sum_limit' => 'gte:0',
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
}
