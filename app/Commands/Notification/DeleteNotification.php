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

namespace App\Commands\Notification;

use App\Models\User;
use App\Repositories\NotificationRepository;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Foundation\EventsDispatchTrait;
use Illuminate\Contracts\Events\Dispatcher;

class DeleteNotification
{
    use AssertPermissionTrait;
    use EventsDispatchTrait;

    /**
     * The ID of the notification to delete.
     *
     * @var int
     */
    public $notificationId;

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
     * @param $notificationId
     * @param User $actor
     * @param array $data
     */
    public function __construct($notificationId, User $actor, array $data = [])
    {
        $this->notificationId = $notificationId;
        $this->actor = $actor;
        $this->data = $data;
    }

    /**
     * @param Dispatcher $events
     * @param NotificationRepository $notification
     * @return void
     * @throws \Discuz\Auth\Exception\NotAuthenticatedException
     */
    public function handle(Dispatcher $events, NotificationRepository $notification)
    {
        $this->events = $events;

        $this->assertRegistered($this->actor);

        $notification = $notification->findOrFail($this->notificationId, $this->actor);

        $notification->forceDelete();
    }
}
