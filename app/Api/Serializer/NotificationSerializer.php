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

        // 默认必须要有的字段
        if (!array_key_exists('reply_post_id', $result)) {
            $result = array_merge($result, [
                'reply_post_id' => 0
            ]);
        }

        // 新增单独赋值的字段值
        $result = array_merge($result, [
            'user_name' => $model->user_name ?: '',
            'user_avatar' => $model->user_avatar ?: '',
            'thread_username' => $model->thread_username ?: '',
            'thread_user_groups' => $model->thread_user_groups ?: '',
            'thread_created_at' => $model->thread_created_at ?: '',
            'thread_is_approved' => $model->thread_is_approved ?: 0,
        ]);

        return $result;
    }
}
