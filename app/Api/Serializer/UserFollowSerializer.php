<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Serializer;

use Discuz\Api\Serializer\AbstractSerializer;
use Tobscure\JsonApi\Relationship;

class UserFollowSerializer extends AbstractSerializer
{
    protected $type = 'follow';

    public function getDefaultAttributes($model)
    {
        return [
            'id' => $model->id,
            'from_user_id' => $model->from_user_id,
            'to_user_id' => $model->to_user_id,
            'is_mutual'  => $model->is_mutual,
            'updated_at' => $this->formatDate($model->updated_at),
            'created_at' => $this->formatDate($model->created_at)
        ];
    }

    /**
     * Define the relationship with the from_user.
     *
     * @param $userFollow
     * @return Relationship
     */
    public function fromUser($userFollow)
    {
        return $this->hasOne($userFollow, UserSerializer::class);
    }

    /**
     * Define the relationship with the to_user.
     *
     * @param $userFollow
     * @return Relationship
     */
    public function toUser($userFollow)
    {
        return $this->hasOne($userFollow, UserSerializer::class);
    }
}
