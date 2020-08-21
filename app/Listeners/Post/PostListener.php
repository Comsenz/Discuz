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

namespace App\Listeners\Post;

use App\Events\Post\Created;
use App\Events\Post\Deleted;
use App\Events\Post\Hidden;
use App\Events\Post\PostWasApproved;
use App\Events\Post\Restored;
use App\Events\Post\Revised;
use App\Events\Post\Saved;
use App\Events\Post\Saving;
use App\Listeners\User\CheckPublish;
use App\MessageTemplate\PostMessage;
use App\MessageTemplate\RepliedMessage;
use App\MessageTemplate\Wechat\WechatPostMessage;
use App\MessageTemplate\Wechat\WechatRepliedMessage;
use App\Models\Attachment;
use App\Models\Post;
use App\Models\PostMod;
use App\Models\Thread;
use App\Models\ThreadTopic;
use App\Models\UserActionLogs;
use App\Notifications\Replied;
use App\Notifications\System;
use App\Traits\PostNoticesTrait;
use Discuz\Api\Events\Serializing;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Auth\Exception\PermissionDeniedException;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class PostListener
{
    use AssertPermissionTrait;
    use PostNoticesTrait;

    public function subscribe(Dispatcher $events)
    {
        // 发表回复
        $events->listen(Saving::class, CheckPublish::class);
        $events->listen(Saving::class, [$this, 'whenPostWasSaving']);
        $events->listen(Created::class, [$this, 'whenPostWasCreated']);
        $events->listen(Created::class, SaveAudioToDatabase::class);

        // 审核回复
        $events->listen(PostWasApproved::class, [$this, 'whenPostWasApproved']);

        // 隐藏/还原回复
        $events->listen(Hidden::class, [$this, 'whenPostWasHidden']);
        $events->listen(Restored::class, [$this, 'whenPostWasRestored']);

        // 删除首帖
        $events->listen(Deleted::class, [$this, 'whenPostWasDeleted']);

        // 修改内容
        $events->listen(Revised::class, [$this, 'whenPostWasRevised']);

        // 喜欢帖子
        $events->listen(Serializing::class, AddPostLikeAttribute::class);
        $events->subscribe(SaveLikesToDatabase::class);

        // 添加完数据后
        $events->listen(Saved::class, [$this, 'whenPostWasSaved']);

        // @
        $events->listen(Saved::class, [$this, 'userMentions']);

        // #话题#
        $events->listen(Saved::class, [$this, 'threadTopic']);
    }

    /**
     * @param Saving $event
     * @throws PermissionDeniedException
     */
    public function whenPostWasSaving(Saving $event)
    {
        $post = $event->post;
        $actor = $event->actor;

        // 是否有权限在该主题所在分类下回复
        if (! $post->exists && ! $post->is_first && $actor->cannot('replyThread', $post->thread->category)) {
            throw new PermissionDeniedException;
        }
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

        if ($post->is_approved == Post::APPROVED) {
            // 如果当前用户不是主题作者，也是合法的，则通知主题作者
            if ($post->thread->user_id != $actor->id) {
                // 数据库通知
                $post->thread->user->notify(new Replied($post, $actor, RepliedMessage::class));

                // 微信通知
                $post->thread->user->notify(new Replied($post, $actor, WechatRepliedMessage::class, [
                    'message' => $post->getSummaryContent(Post::NOTICE_LENGTH, true)['content'],
                    'subject' => $post->getSummaryContent(Post::NOTICE_LENGTH, true)['first_content'],
                    'raw' => array_merge(Arr::only($post->toArray(), ['id', 'thread_id', 'reply_post_id']), [
                        'actor_username' => $actor->username    // 发送人姓名
                    ]),
                ]));
            }

            // 如果被回复的用户不是当前用户，也不是主题作者，也是合法的，则通知被回复的人
            if (
                $post->reply_post_id
                && $post->reply_user_id != $actor->id
                && $post->reply_user_id != $post->thread->user_id
            ) {
                // 数据库通知
                $post->replyUser->notify(new Replied($post, $actor, RepliedMessage::class));

                // 被回复内容
                $post->replyPost->content = Str::of($post->replyPost->content)->substr(0, Post::NOTICE_LENGTH);

                // 微信通知
                $post->replyUser->notify(new Replied($post, $actor, WechatRepliedMessage::class, [
                    'message' => $post->getSummaryContent(Post::NOTICE_LENGTH, true)['content'],
                    'subject' => $post->replyPost->formatContent(), // 解析content
                    'raw' => array_merge(Arr::only($post->toArray(), ['id', 'thread_id', 'reply_post_id']), [
                        'actor_username' => $actor->username    // 发送人姓名
                    ]),
                ]));
            }
        }
    }

    /**
     * 绑定附件 & 刷新被回复数
     *
     * @param Saved $event
     * @throws PermissionDeniedException
     */
    public function whenPostWasSaved(Saved $event)
    {
        $post = $event->post;
        $actor = $event->actor;

        // 绑定附件
        if ($attachments = Arr::get($event->data, 'relationships.attachments.data')) {
            if (! $post->wasRecentlyCreated) {
                $this->assertCan($actor, 'edit', $post);
            }

            $ids = array_column($attachments, 'id');

            // 是否存在未审核的附件
            $unapprovedAttachment = Attachment::query()
                ->where('is_approved', Post::UNAPPROVED)
                ->where('user_id', $actor->id)
                ->whereIn('id', $ids)
                ->exists();

            if ($unapprovedAttachment) {
                $post->is_approved = Post::UNAPPROVED;
                $post->save();
            }

            Attachment::query()
                ->where('user_id', $actor->id)
                ->where('type_id', 0)
                ->whereIn('id', $ids)
                ->update(['type_id' => $post->id]);
        }

        // 刷新主题回复数、最后一条回复
        $thread = $post->thread;

        if ($thread && $thread->exists) {
            $thread->refreshPostCount();
            $thread->refreshLastPost();
            $thread->save();
        }

        // 刷新被回复数
        if ($replyId = $post->reply_post_id) {
            /** @var Post $replyPost */
            $replyPost = Post::query()->find($replyId);
            $replyPost->refreshReplyCount();
            $replyPost->save();
        }
    }

    /**
     * @param PostWasApproved $event
     */
    public function whenPostWasApproved(PostWasApproved $event)
    {
        $post = $event->post;

        if ($post->is_approved === Post::APPROVED) {
            // 审核通过时，清除记录的敏感词
            PostMod::query()->where('post_id', $post->id)->delete();

            $action = 'approve';
        } elseif ($post->is_approved === Post::IGNORED) {
            $action = 'ignore';
        } else {
            $action = 'disapprove';
        }

        // 通知
        $this->postNotices($post, $event->actor, 'isApproved', $event->data['message'] ?? '');

        // 日志
        UserActionLogs::writeLog($event->actor, $post, $action, $event->data['message'] ?? '');
    }

    /**
     * 隐藏回复时
     *
     * @param Hidden $event
     */
    public function whenPostWasHidden(Hidden $event)
    {
        // 通知
        $this->postNotices($event->post, $event->actor, 'isDeleted', $event->data['message'] ?? '');

        // 日志
        UserActionLogs::writeLog($event->actor, $event->post, 'hide', $event->data['message'] ?? '');
    }

    /**
     * 还原回复时
     *
     * @param Restored $event
     */
    public function whenPostWasRestored(Restored $event)
    {
        // 日志
        UserActionLogs::writeLog($event->actor, $event->post, 'restore', $event->data['message'] ?? '');
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
            Thread::query()->where('id', $event->post->thread_id)->delete();

            Post::query()->where('thread_id', $event->post->thread_id)->delete();
        }
    }

    /**
     * 修改内容时，记录操作
     *
     * @param Revised $event
     */
    public function whenPostWasRevised(Revised $event)
    {
        UserActionLogs::writeLog(
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
     * @param Saved $event
     */
    public function userMentions(Saved $event)
    {
        $post = $event->post;

        // 新建 或者 修改了是否合法字段 并且 合法时，发送 @ 通知
        if (($post->wasRecentlyCreated || $post->wasChanged('is_approved')) && $post->is_approved === Post::APPROVED) {
            $this->sendRelated($event->post, $event->post->user);
        }
    }

    /**
     * 解析话题、创建话题、存储话题主题关系、修改话题主题数/阅读数
     * @param Saved $event
     */
    public function threadTopic(Saved $event)
    {
        ThreadTopic::setThreadTopic($event->post);
    }
}
