<?php
declare(strict_types=1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: NotificationSerializer.php 28830 2019-11-06 18:17:00 yanchen $
 */

namespace App\Api\Serializer;

use Discuz\Api\Serializer\AbstractSerializer;

class NotificationSerializer extends AbstractSerializer
{
    protected $type = 'notification';

    public function getDefaultAttributes($model)
    {
        return [
            'id'            => $model->id,
            'user_id'       => $model->notifiable_id,
            'data'          => $model->data,
            'read_at'       => $model->read_at,
        ];
    }
}