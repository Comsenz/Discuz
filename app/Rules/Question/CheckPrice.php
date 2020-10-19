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

namespace App\Rules\Question;

use Discuz\Validation\AbstractRule;
use Exception;

/**
 * Class CheckPrice
 * @package App\Rules\Question
 */
class CheckPrice extends AbstractRule
{
    /**
     * 默认错误提示
     * @var string
     */
    public $message = 'set_error';

    private $actor;

    public function __construct($actor)
    {
        $this->actor = $actor;
    }

    /**
     * @param string $attribute
     * @param mixed $value
     * @return bool|mixed
     * @throws Exception
     */
    public function passes($attribute, $value)
    {
        if ($this->actor->userWallet->available_amount < $value) {
            throw new Exception(trans('wallet.available_amount_error')); // 钱包余额不足
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
