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
use App\Events\Post\Hidden;
use App\Events\Post\PostWasApproved;
use App\Models\OperationLog;
use App\Models\Post;
use App\Models\Thread;
use App\Notifications\Replied;
use Carbon\Carbon;
use Discuz\Api\Events\Serializing;
use Illuminate\Contracts\Events\Dispatcher;

class PostListener
{
    public function subscribe(Dispatcher $events)
    {
        // 绑定附件
        $events->listen(Created::class, [$this, 'whenPostWasCreated']);

        // 审核回复
        $events->listen(PostWasApproved::class, [$this, 'whenPostWasApproved']);

        // 隐藏回复
        $events->listen(Hidden::class, [$this, 'whenPostWasHidden']);

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
     * 审核主题时，记录操作
     *
     * @param PostWasApproved $event
     */
    public function whenPostWasApproved(PostWasApproved $event)
    {
        if ($event->post->is_approved == 1) {
            $action = 'approve';
        } elseif ($event->post->is_approved == 2) {
            $action = 'ignore';
        } else {
            $action = 'disapprove';
        }

        $log = new OperationLog;
        $log->action = $action;
        $log->message = $event->data['message'];
        $log->created_at = Carbon::now();
        $event->post->logs()->save($log);
    }


    /**
     * 隐藏主题时，记录操作
     *
     * @param Hidden $event
     */
    public function whenPostWasHidden(Hidden $event)
    {
        $log = new OperationLog;
        $log->action = 'hide';
        $log->message = $event->data['message'];
        $log->created_at = Carbon::now();
        $event->post->logs()->save($log);
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
