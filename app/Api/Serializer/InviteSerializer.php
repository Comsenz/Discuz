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

class InviteSerializer extends AbstractSerializer
{
    protected $type = 'invite';

    public function getDefaultAttributes($model)
    {
        $attributes =  [
            'group_id' => $model->group_id,
            'type' => $model->type,
            'code' => $model->code,
            'dateline' => $model->dateline,
            'endtime' => $model->endtime,
            'user_id' => $model->user_id,
            'to_user_id' => $model->to_user_id,
            'status' => $model->status,
        ];

        return $attributes;
    }

    /**
     * @param $user
     * @return Relationship
     */
    public function group($user)
    {
        return $this->hasOne($user, GroupSerializer::class);
    }

    /**
     * @param $invite
     * @return Relationship
     */
    protected function user($invite)
    {
        return $this->hasOne($invite, UserSerializer::class);
    }
}
