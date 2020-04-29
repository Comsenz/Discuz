<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Serializer;

use App\Models\Topic;
use Discuz\Api\Serializer\AbstractSerializer;
use Tobscure\JsonApi\Relationship;

class TopicSerializer extends AbstractSerializer
{
    /**
     * {@inheritdoc}
     */
    protected $type = 'topics';

    /**
     * {@inheritdoc}
     *
     * @param Topic $model
     * @return array
     */
    public function getDefaultAttributes($model)
    {
        return [
            'user_id'          => $model->user_id,
            'content'          => $model->content,
            'thread_count'     => $model->thread_count,
            'view_count'       => $model->view_count,
            'updated_at'       => $this->formatDate($model->updated_at),
            'created_at'       => $this->formatDate($model->created_at),
        ];
    }

    /**
     * Define the relationship with the from_user.
     *
     * @param $model
     * @return Relationship
     */
    public function user($model)
    {
        return $this->hasOne($model, UserSerializer::class);
    }

    /**
     * Define the relationship with the to_user.
     *
     * @param $model
     * @return Relationship
     */
    public function threads($model)
    {
        return $this->hasMany($model, ThreadSerializer::class);
    }
}
