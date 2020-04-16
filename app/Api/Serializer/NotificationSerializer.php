<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Serializer;

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
        $result = [
            'id'            => $model->id,
            'user_id'       => $model->notifiable_id,
            'type'          => $model->type,
            'read_at'       => $this->formatDate($model->read_at),
            'created_at'    => $this->formatDate($model->created_at),
        ];

        if (Arr::get($model, 'have_thread', false)) {
            $result = array_merge($result, [
                'thread_id' => $model->thread_id,
                'post_id' => $model->post_id,
                'user_name' => $model->username,
                'user_avatar' => $model->avatar,
                'content' => $model->content,
                'thread_created_at' => $model->thread_created_at,
            ]);
        }

        return $result;
    }
}
