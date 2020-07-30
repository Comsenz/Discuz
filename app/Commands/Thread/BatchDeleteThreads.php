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
use App\Models\User;
use App\Repositories\ThreadRepository;
use Discuz\Foundation\EventsDispatchTrait;
use Illuminate\Contracts\Bus\Dispatcher as BusDispatcher;
use Illuminate\Contracts\Events\Dispatcher;

class BatchDeleteThreads
{
    use EventsDispatchTrait;

    /**
     * The ID array of the threads to delete.
     *
     * @var array
     */
    public $ids;

    /**
     * The user performing the action.
     *
     * @var User
     */
    public $actor;

    /**
     * The attributes of the new thread.
     *
     * @var array
     */
    public $data;

    /**
     * @param array $ids
     * @param User $actor
     * @param array $data
     */
    public function __construct(array $ids, User $actor, array $data = [])
    {
        $this->ids = $ids;
        $this->actor = $actor;
        $this->data = $data;
    }

    /**
     * @param Dispatcher $events
     * @param ThreadRepository $threads
     * @param BusDispatcher $bus
     * @return array
     */
    public function handle(Dispatcher $events, ThreadRepository $threads, BusDispatcher $bus)
    {
        $this->events = $events;

        $result = ['data' => [], 'meta' => []];

        foreach ($this->ids as $id) {
            $thread = $threads->query()->whereVisibleTo($this->actor)->find($id);

            if (! $thread) {
                $result['meta'][] = ['id' => $id, 'message' => 'model_not_found'];
                continue;
            }

            if ($this->actor->can('delete', $thread)) {
                try {
                    $this->events->dispatch(
                        new Deleting($thread, $this->actor, $this->data)
                    );
                } catch (\Exception $e) {
                    $result['meta'][] = ['id' => $id, 'message' => $e->getMessage()];
                    continue;
                }

                $thread->raise(new Deleted($thread));
                $thread->delete();

                //删除视频、视频文件
                $bus->dispatch(
                    new DeleteThreadVideo($thread)
                );

                $result['data'][] = $thread;

                try {
                    $this->dispatchEventsFor($thread, $this->actor);
                } catch (\Exception $e) {
                    $result['meta'][] = ['id' => $id, 'message' => $e->getMessage()];
                    continue;
                }
            } else {
                $result['meta'][] = ['id' => $id, 'message' => 'permission_denied'];
                continue;
            }
        }

        return $result;
    }
}
