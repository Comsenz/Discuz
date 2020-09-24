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

use App\Models\Group;
use Discuz\Api\Serializer\AbstractSerializer;
use Illuminate\Contracts\Routing\UrlGenerator;
use Tobscure\JsonApi\Relationship;

class GroupSerializer extends AbstractSerializer
{
    /**
     * {@inheritdoc}
     */
    protected $type = 'groups';

    /**
     * @var UrlGenerator
     */
    protected $url;

    /**
     * @param UrlGenerator $url
     */
    public function __construct(UrlGenerator $url)
    {
        $this->url = $url;
    }

    /**
     * {@inheritdoc}
     *
     * @param Group $model
     * @return array
     */
    public function getDefaultAttributes($model)
    {
        return [
            'name'              => $model->name,
            'type'              => $model->type,
            'color'             => $model->color,
            'icon'              => $this->getIconUrl($model),
            'default'           => $model->default,
            'isDisplay'         => (bool) $model->is_display,
            'isPaid'            => (bool) $model->is_paid,
            'fee'               => (float) $model->fee,
            'days'              => (int) $model->days,
            'scale'             => $model->scale,
            'is_subordinate'    => (bool) $model->is_subordinate,
            'is_commission'     => (bool) $model->is_commission,
        ];
    }

    /**
     * @param $group
     * @return Relationship
     */
    protected function permission($group)
    {
        return $this->hasMany($group, GroupPermissionSerializer::class);
    }

    /**
     * @param $group
     * @return Relationship
     */
    protected function permissionWithoutCategories($group)
    {
        return $this->hasMany($group, GroupPermissionSerializer::class);
    }

    /**
     * @param Group $group
     * @return null|string
     */
    protected function getIconUrl($group)
    {
        if ($group->icon) {
            return $this->url->to('/storage/' . $group->icon);
        } elseif (in_array($group->id, [Group::ADMINISTRATOR_ID, Group::GUEST_ID])) {
            return $this->url->to("/images/groups/group-{$group->id}.svg");
        } else {
            return $this->url->to('/images/groups/group-10.svg');
        }
    }
}
