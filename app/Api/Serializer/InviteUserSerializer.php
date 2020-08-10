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

class InviteUserSerializer extends AbstractSerializer
{
    protected $type = 'invite_user';

    public function getDefaultAttributes($model)
    {
        return [
            'pid' => $model->pid,
            'user_id' => $model->user_id,
            'invites_code' => $model->invites_code,
            'be_scale' => $model->be_scale,
            'level' => $model->level,
            'updated_at' => $this->formatDate($model->updated_at),
            'created_at' => $this->formatDate($model->created_at),
        ];
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
