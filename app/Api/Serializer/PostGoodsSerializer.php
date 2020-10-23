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

use App\Models\PostGoods;
use Discuz\Api\Serializer\AbstractSerializer;

class PostGoodsSerializer extends AbstractSerializer
{
    protected $type = 'post_goods';

    public function getDefaultAttributes($model)
    {
        return [
            'user_id' => $model->user_id,
            'post_id' => $model->post_id,
            'platform_id' => $model->platform_id,
            'title' => $model->title,
            'image_path' => $model->image_path,
            'price' => $model->price,
            'type' => $model->type,
            'type_name' => PostGoods::enumTypeName($model->type),
            'status' => $model->status,
            'ready_content' => $model->ready_content,
            'detail_content' => $model->detail_content,
            'created_at' => $this->formatDate($model->created_at),
            'updated_at' => $this->formatDate($model->updated_at),
        ];
    }

    public function user($model)
    {
        return $this->hasOne($model, UserSerializer::class);
    }

    public function post($model)
    {
        return $this->hasOne($model, PostSerializer::class);
    }
}
