<?php
declare(strict_types=1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: GroupPermissionValidator.php 28830 2019-10-23 11:54 chenkeke $
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