<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Listeners\Post;

use App\Events\Post\Created;
use App\Events\Post\Deleted;
use App\Events\Post\Hidden;
use App\Events\Post\PostNotices;
use App\Events\Post\PostWasApproved;
use App\Events\Post\Revised;
use App\Events\Post\Saved;
use App\MessageTemplate\PostMessage;
use App\MessageTemplate\Wechat\WechatPostMessage;
use App\Models\Attachment;
use App\Models\OperationLog;
use App\Models\Post;
use App\Models\PostMod;
use App\Models\User;
use App\Models\Thread;
use App\Notifications\Related;
use App\Notifications\Replied;
use App\Notifications\System;
use App\Traits\PostNoticesTrait;
use Discuz\Api\Events\Serializing;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Arr;

class PostListener
{
    use PostNoticesTrait;

    public function subscribe(Dispatcher $events)
    {
        // 发表回复
        $events->listen(Created::class, [$this, 'whenPostWasCreated']);
        $events->listen(Created::class, [$this, 'RelatedPost']);

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

        // 通知主题
        $events->listen(PostNotices::class, [$this, 'whenPostNotices']);
    }

    /**
     * 发送通知
     *
     * @param Created $event
     */
    public function whenPostWasCreated(Created $event)
    {
        $post = $event->post;
        $actor = $event->actor;

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
     * 绑定附件 & 刷新被回复数
     *
     * @param Saved $event
     */
    public function whenPostWasSaved(Saved $event)
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
                ->whereIn('id', $ids)
                ->update(['post_id' => $post->id]);
        }

        // 刷新被回复数
        if ($replyId = $post->reply_post_id) {
            // 回复以及修改、批量修改 全都刷新回复数
            $post = Post::find($replyId);
            $post->refreshReplyCount();
            $post->save();
        }
    }

    /**
     * 审核主题时，记录操作
     *
     * @param PostWasApproved $event
     */
    public function whenPostWasApproved(PostWasApproved $event)
    {
        // 审核通过时，清除记录的敏感词
        PostMod::where('post_id', $event->post->id)->delete();

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
            Thread::where('id', $event->post->thread_id)->delete();

            Post::where('thread_id', $event->post->thread_id)->delete();
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

        if ($event->post->user) {
            // 判断是否是自己
            if ($event->actor->id != $event->post->user->id) {
                $build = [
                    'message' => $event->post->content,
                    'raw' => Arr::only($event->post->toArray(), ['id', 'thread_id', 'is_first'])
                ];
                // 系统通知
                $event->post->user->notify(new System(PostMessage::class, $build));

                // 微信通知
                $event->post->user->notify(new System(WechatPostMessage::class, $build));
            }
        }
    }

    /**
     * 判断用户是否发表内容时是否@其他人
     *
     * @param Created $event
     */
    public function RelatedPost(Created $event)
    {
        $post = $event->post;
        $post_content = $post->content;
        $actor = $event->actor;

        // 过滤多空格，转化HTML代码
        $post_content = preg_replace(['/(\s+)/', '/@/', '/</', '/>/'], [' ', ' @', '&lt;', '&gt;'], $post_content);

        // 用户正则
        $user_pattern = "/@([^\r\n]*?)[:|：|，|,|#|\s]/i";

        // 提取用户
        preg_match_all($user_pattern, $post_content, $userArr);

        if (!empty($userArr[1])) {
            $relatedids = User::whereIn('username', $userArr[1])->where('id', '!=', $actor->id)->get();
            foreach ($relatedids as $relatedid) {
                $relatedid->notify(new Related($post));
            }
        }
    }

    /**
     * 操作回复内容时，发送对应通知
     *
     * @param PostNotices $event
     */
    public function whenPostNotices(PostNotices $event)
    {
        // 判断是修改自己的主题 则不发送通知
        if ($event->post->user_id == $event->actor->id) {
            return;
        }

        switch ($event->data['notice_type']) {
            case 'isApproved':  // 内容审核通知
                $this->postisapproved($event->post, ['refuse' => $this->reasonValue($event->data)]);
                break;
            case 'isDeleted':   // 内容删除通知
                $this->postIsDeleted($event->post, ['refuse' => $this->reasonValue($event->data)]);
                break;
        }
    }
}
