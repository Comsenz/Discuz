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

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\Pivot;
use s9e\TextFormatter\Utils;

/**
 * Models a thread-topic state record in the database.
 *
 * @property int $id
 * @property int $thread_id
 * @property int $topic_id
 * @property Carbon|null $created_at
 * @property Thread $thread
 * @property Topic $topic
 */
class ThreadTopic extends Pivot
{
    const UPDATED_AT = null;

    public $incrementing = true;

    /**
     * 设置主题话题关联关系
     * @param Post $post
     */
    public static function setThreadTopic(Post $post)
    {
        if ($post->is_first) {
            $topics = Utils::getAttributeValues($post->parsedContent, 'TOPIC', 'id');

            $post->thread->topic()->sync($topics);

            $post->thread->topic->each->refreshTopicThreadCount();
        }
    }
}
