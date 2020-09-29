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

namespace App\Api\Controller\Group;

use App\Api\Serializer\GroupSerializer;
use App\Models\Group;
use Discuz\Api\Controller\AbstractListController;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class ListGroupsController extends AbstractListController
{
    /**
     * {@inheritdoc}
     */
    public $serializer = GroupSerializer::class;

    /**
     * {@inheritdoc}
     */
    public $optionalInclude = ['permission'];

    /**
     * {@inheritdoc}
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $include = $this->extractInclude($request);
        $filter = $this->extractFilter($request);

        $isDefault = (bool) Arr::get($filter, 'isDefault', false);
        $type = Arr::get($filter, 'type', '');
        $isPaid = Arr::get($filter, 'isPaid', '');

        $groups = Group::query()
            ->where('id', '<>', Group::UNPAID)
            ->when($isDefault, function (Builder $query) {
                return $query->where('default', true);
            })
            ->when($isPaid != '', function (Builder $query) use ($isPaid) {
                return $query->where('is_paid', (bool) $isPaid);
            })
            ->when($type === 'invite', function (Builder $query) use ($include,$request) {
                // 邀请用户组关联权限不返回 分类下权限
                if (in_array('permission', $include)) {
                    $query->with(['permission' => function ($query) {
                        $query->where('permission', 'not like', 'category%');
                    }]);
                }

                //只有管理员用户组可以展示邀请管理员
                if (!$request->getAttribute('actor')->isAdmin()) {
                    $query->where('id', '<>', Group::ADMINISTRATOR_ID);
                }

                // 不返回游客用户组
                // no guest
                return $query->where('id', '<>', Group::GUEST_ID);
                // or more
                // return $query->whereNotIn('id', [Group::GUEST_ID]);
            });

        return $groups->get()->loadMissing($include);
    }
}
