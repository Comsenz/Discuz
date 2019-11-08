<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: PostValidator.php xxx 2019-10-24 15:10:00 LiuDongdong $
 */

namespace App\Validators;

use Discuz\Foundation\AbstractValidator;

class PostValidator extends AbstractValidator
{
    /**
     * {@inheritdoc}
     */
    protected function getRules()
    {
        return [
            'content' => 'required|max:65535',
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
