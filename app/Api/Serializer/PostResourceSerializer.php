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

use App\Models\Post;
use Discuz\Api\Serializer\AbstractSerializer;
use Illuminate\Contracts\Auth\Access\Gate;
use Tobscure\JsonApi\Relationship;

class PostResourceSerializer extends AbstractSerializer
{
    /**
     * {@inheritdoc}
     */
    protected $type = 'posts';

    /**
     * @var Gate
     */
    protected $gate;

    /**
     * {@inheritdoc}
     *
     * @param Post $model
     */
    public function getDefaultAttributes($model)
    {
        $attributes = [
            'id' => $model->id,
            'user_id' => $model->user_id,
            'thread_id' => $model->thread_id,
            'content' => $model->content,
            'ip' => $model->ip,
            'is_first' => $model->is_first,
            'is_comment' => $model->is_comment,
        ];

        return $attributes;
    }

    /**
     * @param $thread
     * @return Relationship
     */
    protected function user($thread)
    {
        return $this->hasOne($thread, UserSerializer::class);
    }

    /**
     * @param $post
     * @return Relationship
     */
    public function comment_posts($post)
    {
        return $this->hasMany($post, UserActionLogsSerializer::class);
    }
}
