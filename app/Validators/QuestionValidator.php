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

namespace App\Validators;

use App\Rules\Question\CheckOrder;
use App\Rules\Question\CheckPrice;
use App\Rules\Question\UserVerification;
use Discuz\Foundation\AbstractValidator;
use Exception;

class QuestionValidator extends AbstractValidator
{
    /**
     * @return array
     * @throws Exception
     */
    protected function getRules()
    {
        $actor = $this->data['actor'];

        $rule = [
            'be_user_id' => ['required', new UserVerification($actor)], // 被提问的用户
            'price' => ['required', new CheckPrice($actor)], // 提问价格
        ];

        if ($this->data['price']) {
            $rule += ['order_id' => new CheckOrder($actor, $this->data['price'])];
        }

        return $rule;
    }

    /**
     * @return array|string[]
     */
    protected function haveToFields()
    {
        return ['be_user_id', 'price', 'order_id'];
    }

    /**
     * 伪造未传输的关联字段 - 默认值为空
     *
     * @param $str
     * @param $default
     * @return string|int
     */
    protected function faker($str, $default = '')
    {
        if (!array_key_exists($str, $this->data)) {
            return $default;
        }

        return $this->data[$str];
    }
}
