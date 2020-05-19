<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Serializer;

use Carbon\Carbon;
use Discuz\Api\Serializer\AbstractSerializer;
use Illuminate\Support\Arr;

/**
 * Class NotificationSerializer
 *
 * @package App\Api\Serializer
 */
class NotificationSerializer extends AbstractSerializer
{
    protected $type = 'notification';

    public function getDefaultAttributes($model)
    {
        $result = array_merge([
            'id'            => $model->id,
            'type'          => $model->type,
            'user_id'       => $model->notifiable_id,
            'read_at'       => $this->formatDate($model->read_at),
            'created_at'    => $this->formatDate($model->created_at),
        ], $model->data);

        // 新增单独赋值的字段值
        $result = array_merge($result, [
            'user_name' => $model->user_name ?: '',
            'user_avatar' => $model->user_avatar ?: '',
            'thread_user_name' => $model->thread_user_name ?? '',
            'thread_user_groups' => $model->thread_user_groups ?? '',
        ]);

        return $result;
    }
}
