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

use App\Models\Topic;
use Discuz\Api\Serializer\AbstractSerializer;
use Tobscure\JsonApi\Relationship;

class TopicSerializer extends AbstractSerializer
{
    /**
     * {@inheritdoc}
     */
    protected $type = 'topics';

    /**
     * {@inheritdoc}
     *
     * @param Topic $model
     * @return array
     */
    public function getDefaultAttributes($model)
    {
        return [
            'user_id'          => $model->user_id,
            'content'          => $model->content,
            'thread_count'     => $model->thread_count,
            'view_count'       => $model->view_count,
            'recommended'      => $model->recommended,
            'updated_at'       => $this->formatDate($model->updated_at),
            'created_at'       => $this->formatDate($model->created_at),
            'recommended_at'   => $this->formatDate($model->recommended_at),
        ];
    }

    /**
     * Define the relationship with the user.
     *
     * @param $model
     * @return Relationship
     */
    public function user($model)
    {
        return $this->hasOne($model, UserSerializer::class);
    }

    /**
     * Define the relationship with the lastThread.
     *
     * @param $model
     * @return Relationship
     */
    public function lastThread($model)
    {
        return $this->hasMany($model, ThreadSerializer::class);
    }
}
