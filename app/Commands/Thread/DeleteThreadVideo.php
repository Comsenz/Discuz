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

use App\Repositories\ThreadVideoRepository;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Foundation\EventsDispatchTrait;
use Discuz\Qcloud\QcloudTrait;
use Exception;
use Illuminate\Contracts\Events\Dispatcher as EventDispatcher;

class DeleteThreadVideo
{
    use AssertPermissionTrait;
    use EventsDispatchTrait;
    use QcloudTrait;

    public $actor;

    public $thread;

    public function __construct($thread)
    {
        $this->thread = $thread;
    }

    /**
     * @param EventDispatcher $events
     * @param ThreadVideoRepository $threadVideos
     * @throws Exception
     */
    public function handle(EventDispatcher $events, ThreadVideoRepository $threadVideos)
    {
        $this->events = $events;
        if ($this->thread->type == 2) {
            $threadVideo = $threadVideos->query()->where('thread_id', $this->thread->id)->first();
            if ($threadVideo) {
                $threadVideo->delete();
                $this->deleteVodMedia($threadVideo->file_id);
            }
        }
    }
}
