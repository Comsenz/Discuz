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

use App\Models\Category;
use Discuz\Api\Serializer\AbstractSerializer;
use Tobscure\JsonApi\Relationship;

class CategorySerializer extends AbstractSerializer
{
    /**
     * {@inheritdoc}
     */
    protected $type = 'categories';

    /**
     * @param Category $model
     * @return array
     */
    protected function getDefaultAttributes($model)
    {
        return [
            'name'              => $model->name,
            'description'       => $model->description,
            'icon'              => $model->icon,
            'sort'              => (int) $model->sort,
            'property'          => (int) $model->property,
            'thread_count'      => (int) $model->thread_count,
            'ip'                => $model->ip,
            'created_at'        => $this->formatDate($model->created_at),
            'updated_at'        => $this->formatDate($model->updated_at),
            'canViewThreads'    => $this->actor->can('viewThreads', $model),
            'canCreateThread'   => $this->actor->can('createThread', $model),
            'canReplyThread'    => $this->actor->can('replyThread', $model),
            'canEditThread'     => $this->actor->can('thread.edit', $model),
            'canHideThread'     => $this->actor->can('thread.hide', $model),
            'canEssenceThread'  => $this->actor->can('thread.essence', $model),
        ];
    }

    /**
     * @param Category $category
     * @return Relationship
     */
    protected function moderators($category)
    {
        return $this->hasMany($category, UserSerializer::class, 'moderatorUsers');
    }
}
