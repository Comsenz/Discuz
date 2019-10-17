<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: ThreadValidator.php xxx 2019-10-11 18:56:00 LiuDongdong $
 */

namespace App\Validators;

use Discuz\Foundation\AbstractValidator;

class ThreadValidator extends AbstractValidator
{
    /**
     * {@inheritdoc}
     */
    protected function getRules()
    {
        return [
            'content' => 'required|between:5,3000',
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function getMessages()
    {
        return [];
    }
}
