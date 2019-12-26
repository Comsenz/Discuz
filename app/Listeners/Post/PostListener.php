<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Listeners\Post;

use App\Events\Post\Created;
use App\Events\Post\Deleted;
use App\Events\Post\Hidden;
use App\Events\Post\PostWasApproved;
use App\Events\Post\Revised;
use App\Events\Post\Saved;
use App\Models\Attachment;
use App\Models\OperationLog;
use App\Models\Post;
use App\Models\Thread;
use App\Notifications\Replied;
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

        // 添加完数据后
        $events->listen(Saved::class, [$this, 'whenPostWasSaved']);
    }

    /**
     * 在添加完成后触发此事件
     *
     * @param Saved $event
     */
    public function whenPostWasSaved(Saved $event)
    {
        // 要添加回复数的ID
        $replyId = $event->post->reply_post_id;

        // 为null是Created，不需要刷新
        if ($replyId) {
            // 回复以及修改、批量修改 全都刷新回复数
            $post = Post::find($replyId);
            $post->refreshReplyCount();
            $post->save();
        }
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
            $ids = array_column($attachments, 'id');
            // 判断附件是否合法
            $bool = Attachment::approvedInExists($ids);
            if ($bool) {
                // 如果是首贴，将主题设为待审核
                if ($post->is_first) {
                    $post->thread->is_approved = 0;
                }
                $post->is_approved = 0;
            }

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

        OperationLog::writeLog($event->actor, $event->post, $action, $event->data['message']);
    }

    /**
     * 隐藏主题时，记录操作
     *
     * @param Hidden $event
     */
    public function whenPostWasHidden(Hidden $event)
    {
        OperationLog::writeLog($event->actor, $event->post, 'hide', $event->data['message']);
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
        OperationLog::writeLog(
            $event->actor,
            $event->post,
            'revise',
            $event->actor->username . ' 修改了内容'
        );
    }
}
