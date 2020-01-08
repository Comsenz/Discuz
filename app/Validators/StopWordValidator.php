<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Validators;

use Discuz\Foundation\AbstractValidator;

class StopWordValidator extends AbstractValidator
{
    /**
     * {@inheritdoc}
     */
    protected function getRules()
    {
        return [
            'ugc' => 'required|in:{IGNORE},{MOD},{BANNED},{REPLACE}',
            'username' => 'required|in:{IGNORE},{MOD},{BANNED}',
            'find' => 'required|string|unique:stop_words,find|between:1,200',
            'replacement' => 'string|between:1,200',
        ];
    }
}
