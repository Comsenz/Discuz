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

namespace App\Api\Controller\Invite;

use App\Api\Serializer\InviteSerializer;
use App\Api\Serializer\UserInviteSerializer;
use App\Models\Group;
use App\Models\User;
use App\Repositories\InviteRepository;
use Discuz\Api\Controller\AbstractResourceController;
use Illuminate\Contracts\Encryption\Encrypter;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class ResourceInviteController extends AbstractResourceController
{
    /**
     * {@inheritdoc}
     */
    public $serializer = InviteSerializer::class;

    /**
     * {@inheritdoc}
     */
    public $include = ['user', 'group', 'group.permission'];

    /**
     * @var InviteRepository
     */
    protected $inviteRepository;

    /**
     * @var Encrypter
     */
    protected $decrypt;

    /**
     * @param InviteRepository $inviteRepository
     */
    public function __construct(InviteRepository $inviteRepository)
    {
        $this->inviteRepository = $inviteRepository;
    }

    /**
     * {@inheritdoc}
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $code = Arr::get($request->getQueryParams(), 'code');

        if ($this->inviteRepository->lengthByAdmin($code)) {
            $result = $this->inviteRepository->query()
                ->with(['group.permission' => function ($query) {
                    $query->where('permission', 'not like', 'category%');
                }])
                ->where('code', $code)
                ->firstOrFail();
        } else {
            $result = User::query()->findOrFail($code);

            // 查询站点默认用户组
            $groupQuery = Group::query()->where('default', 1);
            $groupQuery->with(['permission' => function ($query) {
                $query->where('permission', 'not like', 'category%');
            }]);
            $groupDefault = $groupQuery->first();

            $result->setRelation('group', $groupDefault);

            $this->serializer = UserInviteSerializer::class;
        }

        return $result;
    }
}
