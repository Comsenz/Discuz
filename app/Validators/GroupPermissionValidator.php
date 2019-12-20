<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Validators;

use Discuz\Foundation\AbstractValidator;

class GroupPermissionValidator extends AbstractValidator
{
    protected function getRules()
    {
        return [
            'permission' => [
                'required',
                'regex:/^[a-zA-Z.]+$/i'
            ],
        ];
    }

    protected function getMessages()
    {
        return [
            'permission.required' => '不能为空',
            'permission.regex' => '权限名称不符合规则'
        ];
    }
}
