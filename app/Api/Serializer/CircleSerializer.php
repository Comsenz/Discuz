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

class CircleSerializer extends AbstractSerializer
{
    protected $type = 'circle';

    public function getDefaultAttributes($model)
    {
        return [
            'id' => $model->id,
            'name' => $model->name,
            'icon' => $model->icon,
            'description' => $model->description,
            'property' => $model->property,
            'threads' => $model->threads,
            'membernum' => $model->membernum,
            'tag_on' => $model->tag_on
        ];
    }
}
