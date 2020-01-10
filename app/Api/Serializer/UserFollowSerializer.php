<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Serializer;

use Discuz\Api\Serializer\AbstractSerializer;

class UserFollowSerializer extends AbstractSerializer
{
    protected $type = 'user_follow';

    public function getDefaultAttributes($model)
    {
        return [
            'id' => $model->id,
            'from_user_id' => $model->from_user_id,
            'to_user_id' => $model->to_user_id
        ];
    }

}
