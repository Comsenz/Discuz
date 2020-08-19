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
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property string $content
 * @property int $thread_count
 * @property int $view_count
 * @property int $recommended
 * @property Carbon $updated_at
 * @property Carbon $created_at
 * @property Carbon $recommended_at
 * @package App\Models
 * @method static find($id)
 * @method static where($column, $fields)
 * @method static firstOrCreate($attributes, $values)
 */
class Topic extends Model
{
    /**
     * {@inheritdoc}
     */
    protected $dates = [
        'updated_at',
        'created_at',
        'recommended_at',
    ];

    /**
     * @var string[]
     */
    protected $fillable = ['user_id', 'content'];

    public $lastThreadId;

    /**
     * refresh thread count
     * 用户删除、帖子审核、帖子逻辑删除不计算
     */
    public function refreshTopicThreadCount()
    {
        $threadCount = ThreadTopic::join('threads', 'threads.id', 'thread_topic.thread_id')
            ->where('thread_topic.topic_id', $this->id)
            ->where('threads.is_approved', Thread::APPROVED)
            ->whereNull('threads.deleted_at')
            ->whereNotNull('user_id')
            ->count();
        $this->thread_count = $threadCount;
        $this->save();
    }

    /**
     * refresh view count
     * 帖子审核、帖子逻辑删除不计算
     */
    public function refreshTopicViewCount()
    {
        $viewCount = ThreadTopic::join('threads', 'threads.id', 'thread_topic.thread_id')
            ->where('thread_topic.topic_id', $this->id)
            ->where('threads.is_approved', Thread::APPROVED)
            ->whereNull('threads.deleted_at')
            ->sum('view_count');
        $this->view_count = $viewCount;
        $this->save();
    }

    /**
     * Define the relationship with the user.
     *
     * @return belongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lastThread()
    {
        return $this->belongsToMany(Thread::class)->withPivot('created_at');
    }
}
