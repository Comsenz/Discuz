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

namespace App\Api\Controller\Permission;

use App\Api\Serializer\GroupPermissionSerializer;
use App\Models\Permission;
use Discuz\Api\Controller\AbstractListController;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Auth\Exception\PermissionDeniedException;
use Discuz\Http\DiscuzResponseFactory;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class UpdateGroupPermissionController extends AbstractListController
{
    use AssertPermissionTrait;

    public $serializer = GroupPermissionSerializer::class;

    /**
     * @var Dispatcher
     */
    protected $bus;

    /**
     * @param Dispatcher $bus
     */
    public function __construct(Dispatcher $bus)
    {
        $this->bus = $bus;
    }

    /**
     * @param ServerRequestInterface $request
     * @param Document $document
     * @return mixed
     * @throws PermissionDeniedException
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $this->assertCan($request->getAttribute('actor'), 'group.edit');

        $data = $request->getParsedBody()->get('data', []);

        $groupId = (int) Arr::get($data, 'attributes.groupId', 0);

        if (! $groupId) {
            return DiscuzResponseFactory::EmptyResponse();
        }

        // 合并默认权限，去空，去重，转换格式
        $permissions = collect(Arr::get($data, 'attributes.permissions'))
            ->merge(Permission::DEFAULT_PERMISSION)
            ->filter()
            ->unique()
            ->map(function ($item) use ($groupId) {
                return [
                    'group_id' => $groupId,
                    'permission' => $item,
                ];
            })
            ->toArray();

        Permission::query()->where('group_id', $groupId)->delete();

        Permission::query()->insert($permissions);

        return DiscuzResponseFactory::EmptyResponse();
    }
}
