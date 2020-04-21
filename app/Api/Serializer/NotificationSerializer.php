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
            'user_name'     => $model->username ?: '',
            'user_avatar'   => $model->avatar ?: '',
            'read_at'       => $this->formatDate($model->read_at),
            'created_at'    => $this->formatDate($model->created_at),
        ], $model->data);

        $result = array_merge($result, [
            'user_name' => $model->username ?: '',
            'user_avatar' => $model->avatar ? $model->avatar . '?' . Carbon::parse($model->avatar_at)->timestamp : '',
        ]);

        return $result;
    }
}
