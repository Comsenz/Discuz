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

namespace App\Commands\Topic;

use App\Models\User;
use App\Repositories\TopicRepository;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Foundation\EventsDispatchTrait;
use Illuminate\Support\Arr;

class EditTopic
{
    use AssertPermissionTrait;
    use EventsDispatchTrait;

    /**
     * The ID of the thread to edit.
     *
     * @var int
     */
    public $topicId;
    /**
     * The attributes to update on the thread.
     *
     * @var array
     */
    public $data;

    /**
     * @param int $threadId The ID of the thread to edit.
     * @param User $actor The user performing the action.
     * @param array $data The attributes to update on the thread.
     */

    public function __construct($topicId, array $data)
    {
        $this->topicId = $topicId;
        $this->data = $data;
    }

    public function handle(TopicRepository $topics)
    {
        $topic = $topics->findOrFail($this->topicId);

        $attributes = Arr::get($this->data, 'attributes', []);
        if (isset($attributes['recommended'])) {
            $topic->recommended = (bool)$attributes['recommended'] ? 1 : 0;
            $topic->recommended_at = date('Y-m-d H:m:s', time());
        }

        $topic->save();

        return $topic;
    }
}
