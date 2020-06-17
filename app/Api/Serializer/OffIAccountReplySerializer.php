<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Serializer;

use Discuz\Api\Serializer\AbstractSerializer;

class OffIAccountReplySerializer extends AbstractSerializer
{
    /**
     * {@inheritdoc}
     */
    protected $type = 'offiaccount_reply';

    /**
     * {@inheritdoc}
     */
    protected function getDefaultAttributes($model)
    {
        return [
            'name' => $model->name,
            'keyword' => $model->keyword,
            'match_type' => $model->match_type,
            'reply_type' => $model->reply_type,
            'media_id' => $model->media_id,
            'media_type' => $model->media_type,
            'type' => $model->type,
            'updated_at' => $this->formatDate($model->updated_at),
            'created_at' => $this->formatDate($model->created_at),
        ];
    }

    public function getId($model)
    {
        return $model->id;
    }
}
