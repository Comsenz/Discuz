<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Serializer;

use Discuz\Api\Serializer\AbstractSerializer;

class NotificationUnreadSerializer extends AbstractSerializer
{
    protected $type = 'notification_unread';

    public function getDefaultAttributes($model)
    {
        return [
//            'name'            => $model->name,
//            'user_id'       => $model->notifiable_id,
//            'data'          => $model->data,
//            'read_at'       => $model->read_at,
        ];
    }
}
