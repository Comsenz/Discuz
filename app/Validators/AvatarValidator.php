<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Validators;

use Discuz\Foundation\AbstractValidator;

class AvatarValidator extends AbstractValidator
{
    /**
     * @return array
     */
    protected function getRules()
    {
        return [
            'avatar' => [
                'required',
                'mimes:jpeg,png,bmp,gif,heic',
                'max:20480'
            ]
        ];
    }
}
