<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Validators;

use Discuz\Foundation\AbstractValidator;

class GroupValidator extends AbstractValidator
{
    protected function getRules()
    {
        return [
            'name' => ['required'],
            'fee' => 'filled|numeric|min:0',
            'days' => 'sometimes|min:0|int'
        ];
    }

    protected function getMessages()
    {
        return [
            'name.required' => '名称不能为空'
        ];
    }
}
