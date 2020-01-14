<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Validators;

use App\Models\User;
use Discuz\Foundation\AbstractValidator;
use Discuz\Contracts\Setting\SettingsRepository;
use Illuminate\Validation\Factory;

class UserValidator extends AbstractValidator
{
    protected $user;

    protected $settings;

    // 获取配置中的密码规则
    protected $setReg = [
        0 => '/\d+/',           // 数字
        1 => '/[a-z]+/',        // 小写字母
        2 => '/[^a-zA-z0-9]+/', // 符号
        3 => '/[A-Z]+/',        // 大写字母
    ];

    public function __construct(Factory $validator, SettingsRepository $settings)
    {
        parent::__construct($validator);
        $this->settings = $settings;
    }

    public function setUser(User $user)
    {
        $this->user = $user;
    }

    protected function getRules()
    {
        $str = $this->getSetting();

        $rules = [
            'username' => [
                'required',
                'regex:/^[A-Za-z0-9_\x{4e00}-\x{9fa5}]+$/u',
                'min:2',
                'max:15',
                'unique:users'
            ],
            'password' => 'required|max:50' . $str,
            'groupId' => 'required',
        ];

        if ($this->user) {
            $rules['password'] = '|confirmed' . $str;
        }

        return $rules;
    }

    protected function getMessages()
    {
        return [
            'username.regex' => '不能有特殊字符',
        ];
    }

    protected function getSetting()
    {
        $res = ['length' => 6, 'regex' => ''];

        if (intval($this->settings->get('password_length')) > $res['length']) {
            $res['length'] = $this->settings->get('password_length');
        }
        $reg = $this->settings->get('password_strength');

        if (filled($reg)) {
            $regColl = collect(explode(',', trim($reg, ',')));
            // Splicing
            $regColl->each(function ($item) use (&$res) {
                $res['regex'] .= '|regex:' . $this->setReg[$item];
            });
        }

        $str = '|min:' . $res['length'] . $res['regex'];

        return $str;
    }
}
