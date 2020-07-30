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

namespace App\Commands\Invite;

use App\Models\Invite;
use App\Models\User;
use App\Repositories\InviteRepository;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Auth\Exception\PermissionDeniedException;
use Discuz\Foundation\EventsDispatchTrait;

class DeleteInvite
{
    use AssertPermissionTrait;
    use EventsDispatchTrait;

    /**
     * The ID of the invite to delete.
     *
     * @var int
     */
    public $inviteId;

    /**
     * The user performing the action.
     *
     * @var User
     */
    public $actor;

    /**
     * 暂未用到，留给插件使用
     *
     * @var array
     */
    public $data;

    /**
     * DeleteInvite constructor.
     * @param $inviteId
     * @param User $actor
     * @param array $data
     */
    public function __construct($inviteId, User $actor, array $data = [])
    {
        $this->inviteId = $inviteId;
        $this->actor = $actor;
        $this->data = $data;
    }

    /**
     * @param InviteRepository $inviteRepository
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     * @throws PermissionDeniedException
     */
    public function handle(InviteRepository $inviteRepository)
    {
        /** @var Invite $invite */
        $invite = $inviteRepository->findOrFail($this->inviteId, $this->actor);

        $this->assertCan($this->actor, 'delete', $invite);
        $invite->status = Invite::STATUS_INVALID;
        $invite->save();

        return $invite;
    }
}
