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

namespace App\Commands\StopWord;

use App\Events\StopWord\Deleting;
use App\Models\StopWord;
use App\Models\User;
use App\Repositories\StopWordRepository;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Foundation\EventsDispatchTrait;
use Exception;
use Illuminate\Contracts\Events\Dispatcher;

class DeleteStopWord
{
    use AssertPermissionTrait;
    use EventsDispatchTrait;

    /**
     * The ID of the stop word to delete.
     *
     * @var int
     */
    public $stopWordId;

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
     * @param int $stopWordId
     * @param User $actor
     * @param array $data
     */
    public function __construct($stopWordId, User $actor, array $data = [])
    {
        $this->stopWordId = $stopWordId;
        $this->actor = $actor;
        $this->data = $data;
    }

    /**
     * @param Dispatcher $events
     * @param StopWordRepository $stopWords
     * @return StopWord
     * @throws Exception
     */
    public function handle(Dispatcher $events, StopWordRepository $stopWords)
    {
        $this->events = $events;

        $stopWord = $stopWords->findOrFail($this->stopWordId, $this->actor);

        $this->assertCan($this->actor, 'delete', $stopWord);

        $this->events->dispatch(
            new Deleting($stopWord, $this->actor, $this->data)
        );

        $stopWord->delete();

        $this->dispatchEventsFor($stopWord, $this->actor);

        return $stopWord;
    }
}
