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

namespace App\Api\Controller\Users;

use App\Models\DenyUser;
use Discuz\Api\Controller\AbstractDeleteController;
use Discuz\Auth\AssertPermissionTrait;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;

class DeleteDenyUserController extends AbstractDeleteController
{
    use AssertPermissionTrait;

    /**
     * @inheritDoc
     * @throws \Discuz\Auth\Exception\NotAuthenticatedException
     */
    protected function delete(ServerRequestInterface $request)
    {
        $actor = $request->getAttribute('actor');

        $this->assertRegistered($actor);

        $id = Arr::get($request->getQueryParams(), 'id');

        if ($actor->deny) {
            DenyUser::query()->where([
                'user_id' => $actor->id,
                'deny_user_id' => $id
            ])->delete();
        }
    }
}
