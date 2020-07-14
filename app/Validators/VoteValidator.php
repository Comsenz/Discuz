<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Validators;

use Discuz\Foundation\AbstractValidator;

class VoteValidator extends AbstractValidator
{
    /**
     * {@inheritdoc}
     */
    protected function getRules()
    {
        return [
            'thread_id'     => 'filled|int',
            'type'          => 'required|int',
            'start_at'      => 'filled|date',
            'end_at'        => 'required|date',
            'content'      => 'array|min:2',
        ];
    }
}
