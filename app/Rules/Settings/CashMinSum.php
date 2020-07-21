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
