<?php

/**
 * Copyright (C) 2020 Tencent Cloud.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace App\Api\Serializer;

use Discuz\Api\Serializer\AbstractSerializer;

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
            'id' => $model->id,
            'type' => $model->type,
            'user_id' => $model->notifiable_id,
            'read_at' => $this->formatDate($model->read_at),
            'created_at' => $this->formatDate($model->created_at),
        ], $model->data);

        // 默认必须要有的字段
        if (! array_key_exists('reply_post_id', $result)) {
            $result = array_merge($result, [
                'reply_post_id' => 0
            ]);
        } else {
            // 返回楼中楼数据
            $result = array_merge($result, [
                'reply_post_user_name' => $model->reply_post_user_name
            ]);
        }

        // 新增单独赋值的字段值
        $result = array_merge($result, [
            'user_name' => $model->user_name ?: '',
            'user_avatar' => $model->user_avatar ?: '',
            'isReal' => $this->getIsReal($model->realname),
            'thread_username' => $model->thread_username ?: '',
            'thread_user_groups' => $model->thread_user_groups ?: '',
            'thread_created_at' => $model->thread_created_at ?: '',
            'thread_is_approved' => $model->thread_is_approved ?: 0,
        ]);

        // 判断是否要匿名
        if (isset($model->isAnonymous) && $model->isAnonymous) {
            $result['user_id'] = -1;
            $result['isReal'] = false; // 全部默认未认证
            $result['isAnonymous'] = $model->isAnonymous;
        }

        return $result;
    }

    /**
     * 是否实名认证
     *
     * @param $realname
     * @return string
     */
    public function getIsReal($realname)
    {
        if (isset($realname) && $realname != null) {
            return true;
        } else {
            return false;
        }
    }
}
