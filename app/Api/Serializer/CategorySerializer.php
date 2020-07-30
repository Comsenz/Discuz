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

class CategorySerializer extends AbstractSerializer
{
    /**
     * {@inheritdoc}
     */
    protected $type = 'categories';

    /**
     * {@inheritdoc}
     */
    protected function getDefaultAttributes($model)
    {
        return [
            'name'              => $model->name,
            'description'       => $model->description,
            'icon'              => $model->icon,
            'sort'              => $model->sort,
            'property'          => $model->property,
            'thread_count'      => (int) $model->thread_count,
            'ip'                => $model->ip,
            'created_at'        => $this->formatDate($model->created_at),
            'updated_at'        => $this->formatDate($model->updated_at),
            'canViewThreads'    => $this->actor->can('viewThreads', $model),
            'canCreateThread'   => $this->actor->can('createThread', $model),
            'canReplyThread'    => $this->actor->can('replyThread', $model),
        ];
    }
}
