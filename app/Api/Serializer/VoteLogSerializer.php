<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Serializer;

use App\Models\VoteLog;
use App\Models\VoteOption;
use Discuz\Api\Serializer\AbstractSerializer;
use Tobscure\JsonApi\Relationship;

class VoteLogSerializer extends AbstractSerializer
{
    /**
     * {@inheritdoc}
     */
    protected $type = 'vote-options';

    /**
     * {@inheritdoc}
     *
     * @param VoteLog $model
     * @return array
     */
    public function getDefaultAttributes($model)
    {
        return [
            'user_id'          => $model->user_id,
            'vote_id'          => $model->vote_id,
            'option_id'        => $model->option_id,
            'ip'               => $model->ip,
            'updated_at'       => $this->formatDate($model->updated_at),
            'created_at'       => $this->formatDate($model->created_at),
        ];
    }

    public function user($model)
    {
        return $this->hasOne($model, UserSerializer::class);
    }

    public function vote($model)
    {
        return $this->hasOne($model, VoteSerializer::class);
    }

    public function option($model)
    {
        return $this->hasOne($model, VoteOptionSerializer::class);
    }

}
