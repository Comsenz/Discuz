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

namespace App\Commands\Thread;

use App\Events\Thread\Deleted;
use App\Events\Thread\Deleting;
use App\Models\Thread;
use App\Models\User;
use App\Repositories\ThreadRepository;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Auth\Exception\PermissionDeniedException;
use Discuz\Foundation\EventsDispatchTrait;
use Illuminate\Contracts\Bus\Dispatcher as BusDispatcher;
use Illuminate\Contracts\Events\Dispatcher;

class DeleteThread
{
    use AssertPermissionTrait;
    use EventsDispatchTrait;

    /**
     * The ID of the thread to delete.
     *
     * @var int
     */
    public $threadId;

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
     * @param int $threadId
     * @param User $actor
     * @param array $data
     */
    public function __construct($threadId, User $actor, array $data = [])
    {
        $this->threadId = $threadId;
        $this->actor = $actor;
        $this->data = $data;
    }

    /**
     * @param Dispatcher $events
     * @param ThreadRepository $threads
     * @param BusDispatcher $bus
     * @return Thread
     * @throws PermissionDeniedException
     */
    public function handle(Dispatcher $events, ThreadRepository $threads, BusDispatcher $bus)
    {
        $this->events = $events;

        $thread = $threads->findOrFail($this->threadId, $this->actor);

        $this->assertCan($this->actor, 'delete', $thread);

        $this->events->dispatch(
            new Deleting($thread, $this->actor, $this->data)
        );

        $thread->raise(new Deleted($thread));
        $thread->delete();

        //删除视频、视频文件
        $bus->dispatch(
            new DeleteThreadVideo($thread)
        );

        $this->dispatchEventsFor($thread, $this->actor);

        return $thread;
    }
}
