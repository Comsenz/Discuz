<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Serializer;

use App\Models\Emoji;
use Discuz\Api\Serializer\AbstractSerializer;

class EmojiSerializer extends AbstractSerializer
{
    /**
     * {@inheritdoc}
     */
    protected $type = 'emoji';

    /**
     * {@inheritdoc}
     *
     * @param Emoji $model
     */
    public function getDefaultAttributes($model)
    {
        return [
            'category'          => $model->category,
            // 'url'               => $model->url,
            'url'               => $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'] . '/' . $model->url,
            'code'              => $model->code,
            'order'             => $model->order,
        ];
    }
}
