<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Serializer;

use Discuz\Api\Serializer\AbstractSerializer;

class ThreadVideoSerializer extends AbstractSerializer
{
    /**
     * {@inheritdoc}
     */
    protected $type = 'thread-video';

    /**
     * {@inheritdoc}
     *
     */
    public function getDefaultAttributes($model)
    {
        return [
            'user_id'        => $model->user_id,
            'thread_id'      => $model->thread_id,
            'status'         => $model->status,
            'reason'         => $model->reason,
            'file_name'      => $model->file_name,
            'file_id'        => $model->file_id,
            'media_url'      => $model->media_url,
            'cover_url'      => $model->cover_url,
            'updated_at'     => $this->formatDate($model->updated_at),
            'created_at'     => $this->formatDate($model->created_at)
        ];
    }
}
