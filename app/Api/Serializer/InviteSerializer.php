<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Serializer;

use Discuz\Api\Serializer\AbstractSerializer;
use Tobscure\JsonApi\Relationship;

class InviteSerializer extends AbstractSerializer
{
    protected $type = 'invite';

    public function getDefaultAttributes($model)
    {
        return [
            'group_id' => $model->group_id,
            'type' => $model->type,
            'code' => $model->code,
            'dateline' => $model->dateline,
            'endtime' => $model->endtime,
            'user_id' => $model->user_id,
            'to_user_id' => $model->to_user_id,
            'status' => $model->status,
        ];
    }

    /**
     * @param $user
     * @return Relationship
     */
    public function group($user)
    {
        return $this->hasOne($user, GroupSerializer::class);
    }
}
