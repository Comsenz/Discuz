<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Validators;

use Discuz\Foundation\AbstractValidator;

class CategoryValidator extends AbstractValidator
{
    protected function getRules()
    {
        return [
            'name' => 'required|min:1|max:10',
            'description' => 'max:200',
        ];
    }

    protected function getMessages()
    {
        return [];
    }
}
