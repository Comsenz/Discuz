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
use App\Events\Post\Revised;
use App\Models\Attachment;
use App\Models\OperationLog;
use App\Models\Post;
use App\Models\Thread;
use App\Notifications\Replied;
use Carbon\Carbon;
use Discuz\Api\Events\Serializing;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Arr;

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

        // 修改内容
        $events->listen(Revised::class, [$this, 'whenPostWasRevised']);

        // 喜欢帖子
        $events->listen(Serializing::class, AddPostLikeAttribute::class);
        $events->subscribe(SaveLikesToDatabase::class);
    }

    /**
     * 绑定附件 & 发送通知
     *
     * @param Created $event
     */
    public function whenPostWasCreated(Created $event)
    {
        $post = $event->post;
        $actor = $event->actor;

        // 绑定附件
        if ($attachments = Arr::get($event->data, 'relationships.attachments.data')) {
            Attachment::where('user_id', $actor->id)
                ->where('post_id', 0)
                ->whereIn('id', array_column($attachments, 'id'))
                ->update(['post_id' => $post->id]);
        }

        // 如果当前用户不是主题作者，则通知主题作者
        if ($post->thread->user_id != $actor->id) {
            $post->thread->user->notify(new Replied($post));
        }

        // 如果被回复的用户不是当前用户，也不是主题作者，则通知被回复的人
        if (
            $post->reply_post_id
            && $post->reply_user_id != $actor->id
            && $post->reply_user_id != $post->thread->user_id
        ) {
            $post->replyUser->notify(new Replied($post));
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
        $log->user_id = $event->actor->id;
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
        $log->user_id = $event->actor->id;
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

    /**
     * 修改内容时，记录操作
     *
     * @param Revised $event
     */
    public function whenPostWasRevised(Revised $event)
    {
        $log = new OperationLog;
        $log->user_id = $event->actor->id;
        $log->action = 'revise';
        $log->message = $event->actor->username . ' 修改了内容';
        $log->created_at = Carbon::now();
        $event->post->logs()->save($log);
    }
}
