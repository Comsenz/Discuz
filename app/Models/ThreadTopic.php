<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Arr;
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
        $blocks = $post->content->get('blocks');
        $topicIds = [];
        foreach ($blocks as $block) {
            if ($block['type'] == 'text' && isset($block['data']['topics'])) {
                foreach ($block['data']['topics'] as $topic) {
                    $topicIds[] = Arr::get($topic, 'id');
                }
            }
        }

        if ($post->is_first) {
            $post->thread->topic()->sync($topicIds);

            $post->thread->topic->each->refreshTopicThreadCount();
        }

    }
}
