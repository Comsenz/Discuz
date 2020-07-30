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

class UserWechatSerializer extends AbstractSerializer
{
    protected $type = 'wechats';

    /**
     * Get the default set of serialized attributes for a model.
     *
     * @param object|array $model
     * @return array
     */
    protected function getDefaultAttributes($model)
    {
        return [
            'mp_openid'         => $model->mp_openid,
            'dev_openid'         => $model->dev_openid,
            'min_openid'         => $model->min_openid,
            'nickname'          => $model->nickname,
            'sex'               => $model->sex, // 用户的性别，值为 1 时是男性，值为 2 时是女性，值为 0 时是未知
        ];
    }
}
