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

use App\Models\User;
use App\Rules\Captcha;
use Discuz\Contracts\Setting\SettingsRepository;
use Discuz\Foundation\AbstractValidator;
use Illuminate\Validation\Factory;

class UserValidator extends AbstractValidator
{
    /**
     * @var User
     */
    protected $user;

    /**
     * @var SettingsRepository
     */
    protected $settings;

    /**
     * The default minimum password length.
     *
     * @var int
     */
    protected $passwordLength = 6;

    /**
     * The default password strength.
     *
     * @var array
     */
    protected $passwordStrength = [];

    /**
     * The optional regular password strength.
     *
     * @var array
     */
    protected $optionalPasswordStrengthRegex = [
        [
            'name' => '数字',
            'pattern' => '/\d+/',
        ],
        [
            'name' => '小写字母',
            'pattern' => '/[a-z]+/',
        ],
        [
            'name' => '符号',
            'pattern' => '/[^a-zA-z0-9]+/',
        ],
        [
            'name' => '大写字母',
            'pattern' => '/[A-Z]+/',
        ],
    ];

    /**
     * 获取相关设置
     *
     * @param Factory $validator
     * @param SettingsRepository $settings
     */
    public function __construct(Factory $validator, SettingsRepository $settings)
    {
        parent::__construct($validator);

        $this->settings = $settings;

        // 获取后台设置的密码长度
        $settingsPasswordLength = (int) $settings->get('password_length');

        // 获取后台设置的密码强度
        $settingsPasswordStrength = array_filter(explode(',', trim($settings->get('password_strength'), ',')));

        // 后台设置的长度大于默认长度时，使用后台设置的长度
        $this->passwordLength = $settingsPasswordLength > $this->passwordLength
            ? $settingsPasswordLength
            : $this->passwordLength;

        // 使用后台设置的密码强度
        $this->passwordStrength = $settingsPasswordStrength ?: $this->passwordStrength;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }

    /**
     * {@inheritdoc}
     */
    protected function getRules()
    {
        $rules = [
            'username' => [
                'required',
                'max:15',
                'unique:users',
                function ($attribute, $value, $fail) {
                    if ($value === '匿名用户') {
                        $fail('无效的用户名。');
                    }
                },
            ],
            'password' => $this->getPasswordRules(),
            'pay_password' => 'bail|sometimes|required|confirmed|digits:6',
            'pay_password_token' => 'sometimes|required|session_token:reset_pay_password',
            'register_reason' => 'filled|max:50',
            'groupId' => 'required',
            'realname' => 'required',
            'identity' => ['required', 'regex:/(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/', 'unique:users'],
            'captcha' => ['sometimes', new Captcha],
        ];

        if ($this->user) {
            $rules['password'][] = 'confirmed';
            $rules['pay_password_token'] .= ',' . $this->user->id;
        }

        return $rules;
    }

    /**
     * {@inheritdoc}
     */
    protected function getMessages()
    {
        $messages = [
            'username.regex' => trans('user.username_error'),
            'identity.regex' => trans('user.identity_error'),
        ];

        // 密码强度
        if ($this->passwordStrength) {
            $passwordRegexMessage = [];

            collect($this->passwordStrength)->each(function ($regex) use (&$passwordRegexMessage) {
                $passwordRegexMessage[] = $this->optionalPasswordStrengthRegex[$regex]['name'];
            });

            $messages['password.regex'] = '密码格式不正确，必须包含' . implode('、', $passwordRegexMessage) . '。';
        }

        return $messages;
    }

    /**
     * @return array
     */
    protected function getPasswordRules()
    {
        $rules = [
            'required',
            'max:50',
            'min:' . $this->passwordLength,
        ];

        // 密码强度
        if ($this->passwordStrength) {
            collect($this->passwordStrength)->each(function ($regex) use (&$rules) {
                $rules[] = 'regex:' . $this->optionalPasswordStrengthRegex[$regex]['pattern'];
            });
        }

        return $rules;
    }
}
