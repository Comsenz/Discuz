<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Serializer;

use App\Models\VoteOption;
use Discuz\Api\Serializer\AbstractSerializer;
use Tobscure\JsonApi\Relationship;

class VoteOptionSerializer extends AbstractSerializer
{
    /**
     * {@inheritdoc}
     */
    protected $type = 'vote-option';

    /**
     * {@inheritdoc}
     *
     * @param VoteOption $model
     * @return array
     */
    public function getDefaultAttributes($model)
    {
        return [
            'vote_id'          => $model->vote_id,
            'content'          => $model->content,
            'count'            => $model->count,
            'updated_at'       => $this->formatDate($model->updated_at),
            'created_at'       => $this->formatDate($model->created_at),
        ];
    }

    public function vote($model)
    {
        return $this->hasOne($model, VoteSerializer::class);
    }
}
