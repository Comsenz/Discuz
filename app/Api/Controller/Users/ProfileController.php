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

use App\Api\Serializer\UserProfileSerializer;
use App\Api\Serializer\UserSerializer;
use App\Models\Dialog;
use App\Models\Group;
use App\Models\User;
use App\Repositories\UserFollowRepository;
use App\Repositories\UserRepository;
use Discuz\Api\Controller\AbstractResourceController;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Contracts\Setting\SettingsRepository;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;
use Tobscure\JsonApi\Exception\InvalidParameterException;

class ProfileController extends AbstractResourceController
{
    use AssertPermissionTrait;

    public $serializer = UserSerializer::class;

    public $optionalInclude = ['groups', 'dialog'];

    protected $users;

    protected $userFollow;

    protected $settings;

    public function __construct(UserRepository $users, UserFollowRepository $userFollow, SettingsRepository $settings)
    {
        $this->users = $users;
        $this->userFollow = $userFollow;
        $this->settings = $settings;
    }

    /**
     * @param ServerRequestInterface $request
     * @param Document $document
     * @return mixed
     * @throws InvalidParameterException
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $actor = $request->getAttribute('actor');

        $id = Arr::get($request->getQueryParams(), 'id');

        $user = $this->users->findOrFail($id, $actor);
        $isSelf = $user->id === $actor->id;

        if ($isSelf || $actor->isAdmin()) {
            $this->optionalInclude = array_merge($this->optionalInclude, ['wechat']);
        }

        if ($isSelf) {
            $this->serializer = UserProfileSerializer::class;
        }

        // 付费模式是否过期
        $user->paid = ! in_array(Group::UNPAID, $actor->groups->pluck('id')->toArray());

        $include = $this->extractInclude($request);

        $key = array_search('dialog', $include);
        if ($key !== false) {
            if (!$isSelf) {
                //添加会话关系
                $dialog = Dialog::query()
                    ->where(['sender_user_id' => $actor->id, 'recipient_user_id' => $user->id])
                    ->orWhere(function ($query) use ($actor, $user) {
                        $query->where(['sender_user_id' => $user->id, 'recipient_user_id' => $actor->id]);
                    })
                    ->first();
                $user->setRelation('dialog', $dialog);
            } else {
                unset($include[$key]);
            }
        }
        $user->loadMissing($include);

        // 判断用户是否禁用
        if ($user->status == User::enumStatus('ban')) {
            $user->load(['latelyLog' => function ($query) {
                $query->select()->where('action', 'ban');
            }]);
        }

        return $user;
    }
}
