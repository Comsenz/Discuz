<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: PostListener.php xxx 2019-11-04 09:48:00 LiuDongdong $
 */

namespace App\Listeners\Post;

use App\Events\Post\Created;
use App\Events\Post\Deleted;
use App\Models\Post;
use App\Models\Thread;
use App\Notifications\Replied;
use Discuz\Api\Events\Serializing;
use Illuminate\Contracts\Events\Dispatcher;

class PostListener
{
    public function subscribe(Dispatcher $events)
    {
        // 删除首帖
        $events->listen(Deleted::class, [$this, 'whenPostWasDeleted']);

        // 喜欢帖子
        $events->listen(Serializing::class, AddPostLikeAttribute::class);
        $events->subscribe(SaveLikesToDatabase::class);
    }

    /**
     * TODO: 绑定附件
     * 发送通知
     *
     * @param Created $event
     */
    public function whenPostWasCreated(Created $event)
    {
        $post = $event->post;
        $actor = $event->actor;

        // 通知被回复的人
        if ($event->post->reply_id) {
            $replyPost = Post::find($post->reply_id);

            $info = [
                'username' => $actor->username,
                'user_id' => $actor->id,
                'info' => '回复了我的帖子',
                'post_id' => $post->id,
                'reply_id' => $post->reply_id,
                'thread_id' => $post->thread_id,
                'post_content' => $post->content,
            ];
            $replyPost->user->notify(new Replied($info));
        }
    }

    /**
     * TODO: 删除附件
     * 如果删除的是首帖，同时删除主题及主题下所有回复
     *
     * @param Deleted $event
     */
    public function whenPostWasDeleted(Deleted $event)
    {
        if ($event->post->is_first) {
            Thread::where('id', $event->post->thread_id)->forceDelete();

            Post::where('thread_id', $event->post->thread_id)->forceDelete();
        }
    }
}
