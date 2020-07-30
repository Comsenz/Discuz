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

use App\Api\Serializer\UserSerializer;
use App\Repositories\UserRepository;
use App\User\AvatarUploader;
use Discuz\Api\Controller\AbstractResourceController;
use Discuz\Auth\AssertPermissionTrait;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class DeleteAvatarController extends AbstractResourceController
{
    use AssertPermissionTrait;

    /**
     * {@inheritdoc}
     */
    public $serializer = UserSerializer::class;

    /**
     * @var UserRepository
     */
    protected $users;

    /**
     * @var AvatarUploader
     */
    protected $uploader;

    /**
     * @param UserRepository $users
     * @param AvatarUploader $uploader
     */
    public function __construct(UserRepository $users, AvatarUploader $uploader)
    {
        $this->users = $users;
        $this->uploader = $uploader;
    }

    /**
     * @param ServerRequestInterface $request
     * @param Document $document
     * @return \App\Models\User|mixed
     * @throws \Discuz\Auth\Exception\PermissionDeniedException
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $actor = $request->getAttribute('actor');
        $id = Arr::get($request->getQueryParams(), 'id');

        $user = $this->users->findOrFail($id);

        $this->assertCan($actor, 'edit', $user);

        $this->uploader->remove($user);

        $user->save();

        return $user;
    }
}
