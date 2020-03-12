<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Validators;

use App\Models\User;
use Discuz\Contracts\Setting\SettingsRepository;
use Discuz\Foundation\AbstractValidator;
use Discuz\Qcloud\QcloudTrait;
use Illuminate\Validation\Factory;

class UserValidator extends AbstractValidator
{
    use QcloudTrait;

    /**
     * @var User
     */
    protected $user;

    /**
     * @var SettingsRepository
     */
    protected $settings;

    /**
     * 腾讯云验证码开关。
     *
     * @var bool
     */
    protected $qCloudCaptchaSwitch = false;

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

        // 获取后台设置的腾讯云验证码开关
        $this->qCloudCaptchaSwitch = (bool)$settings->get('qcloud_captcha', 'qcloud');

        // 获取后台设置的密码长度
        $settingsPasswordLength = (int)$settings->get('password_length');

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
                'regex:/^[A-Za-z0-9_\x{4e00}-\x{9fa5}]+$/u',
                'min:2',
                'max:15',
                'unique:users'
            ],
            'password' => $this->getPasswordRules(),
            'pay_password' => 'sometimes|required|confirmed|digits:6',
            'pay_password_token' => 'sometimes|required|session_token:reset_pay_password',
            'groupId' => 'required',
            'identity' => [
                'required',
                'regex:/(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/',
            ],
            'realname' => 'required',
        ];

        if ($this->user) {
            $rules['password'][] = 'confirmed';
            $rules['pay_password_token'] .= ',' . ($this->user ? $this->user->id : null);
        }

        // 当腾讯云验证码开启时，且数据中有 captcha 时，检查验证码
        if ($this->qCloudCaptchaSwitch) {
            $rules['captcha'] = [
                'sometimes',
                function ($attribute, $value, $fail) {
                    if (count(array_filter($value)) == 3) {
                        $result = $this->describeCaptchaResult(...$value);

                        if ($result['CaptchaCode'] != 1) {
                            $fail(trans('validation.wrong'));
                        }
                    } else {
                        $fail(trans('validation.wrong'));
                    }
                },
            ];
        }

        return $rules;
    }

    /**
     * {@inheritdoc}
     */
    protected function getMessages()
    {
        $messages = [
            'username.regex' => '用户名不能有特殊字符。',
            'identity.regex' => '身份证为 15 位或 18 位。',
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
