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
use Tobscure\JsonApi\Relationship;

class UserFollowSerializer extends AbstractSerializer
{
    protected $type = 'follow';

    public function getDefaultAttributes($model)
    {
        return [
            'id' => $model->id,
            'from_user_id' => $model->from_user_id,
            'to_user_id' => $model->to_user_id,
            'is_mutual'  => $model->is_mutual,
            'updated_at' => $this->formatDate($model->updated_at),
            'created_at' => $this->formatDate($model->created_at)
        ];
    }

    /**
     * Define the relationship with the from_user.
     *
     * @param $userFollow
     * @return Relationship
     */
    public function fromUser($userFollow)
    {
        return $this->hasOne($userFollow, UserSerializer::class);
    }

    /**
     * Define the relationship with the to_user.
     *
     * @param $userFollow
     * @return Relationship
     */
    public function toUser($userFollow)
    {
        return $this->hasOne($userFollow, UserSerializer::class);
    }
}
