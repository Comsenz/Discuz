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

use App\Models\User;
use Discuz\Validation\AbstractRule;

/**
 * Class UserVerification
 * @package App\Rules\Question
 */
class UserVerification extends AbstractRule
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
     */
    public function passes($attribute, $value)
    {
        // 不允许被提问人是自己
        if ($this->actor->id != $value) {
            /**
             *  查询被提问人是否允许被提问
             *
             *  @var User $user
             */
            $user = User::query()->where('id', $value)->first();
            if ($user->can('canBeAsked')) {
                return true;
            }

            // 被提问用户没有权限回答
            $this->message = 'post.post_question_ask_be_user_permission_denied';
            return false;
        }

        // 不能向自己提问
        $this->message = 'post.post_question_ask_yourself_fail';

        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string|array
     */
    public function message()
    {
        // 不能向自己提问
        return trans($this->message);
    }
}
