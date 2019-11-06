<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: StopWordValidator.php xxx 2019-11-05 18:22:00 LiuDongdong $
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
            'ugc' => 'required|in:{MOD},{BANNED},{REPLACE}',
            'username' => 'required|in:{MOD},{BANNED},{REPLACE}',
            'find' => 'required|string|unique:stop_words,find|between:1,200',
            'replacement' => 'string|between:1,200',
        ];
    }
}
