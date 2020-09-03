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

use App\Models\Permission;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Auth\Exception\PermissionDeniedException;
use Discuz\Http\DiscuzResponseFactory;
use Illuminate\Support\Arr;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class SetPermissionController implements RequestHandlerInterface
{
    use AssertPermissionTrait;

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws PermissionDeniedException
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $this->assertAdmin($request->getAttribute('actor'));

        $body = $request->getParsedBody();
        $permission = Arr::get($body, 'permission');
        $groupIds = Arr::get($body, 'groupIds', []);

        Permission::query()->where('permission', $permission)->delete();

        Permission::query()->insert(array_map(function ($groupId) use ($permission) {
            return [
                'permission' => $permission,
                'group_id' => $groupId
            ];
        }, $groupIds));

        return DiscuzResponseFactory::EmptyResponse();
    }
}
