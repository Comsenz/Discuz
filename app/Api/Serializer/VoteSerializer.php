<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Serializer;

use App\Models\Vote;
use Discuz\Api\Serializer\AbstractSerializer;
use Tobscure\JsonApi\Relationship;

class VoteSerializer extends AbstractSerializer
{
    /**
     * {@inheritdoc}
     */
    protected $type = 'votes';

    /**
     * {@inheritdoc}
     *
     * @param Vote $model
     * @return array
     */
    public function getDefaultAttributes($model)
    {
        return [
            'name'             => $model->name,
            'user_id'          => $model->user_id,
            'thread_id'        => $model->thread_id,
            'type'             => $model->type,
            'total_count'      => $model->total_count,
            'start_at'         => $this->formatDate($model->start_at),
            'end_at'           => $this->formatDate($model->end_at),
            'updated_at'       => $this->formatDate($model->updated_at),
            'created_at'       => $this->formatDate($model->created_at),
        ];
    }

    public function user($model)
    {
        return $this->hasOne($model, UserSerializer::class);
    }

    public function thread($model)
    {
        return $this->hasOne($model, ThreadSerializer::class);
    }

    public function options($model)
    {
        return $this->hasMany($model, VoteOptionSerializer::class);
    }
}
