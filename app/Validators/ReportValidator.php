<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Validators;

use Discuz\Foundation\AbstractValidator;

class ReportValidator extends AbstractValidator
{
    protected function getRules()
    {
        return [
            'user_id' => 'required',
            'type' => 'required',
            'reason' => 'required',
        ];
    }

    protected function getMessages()
    {
        return [];
    }
}
