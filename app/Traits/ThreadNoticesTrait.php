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

namespace App\Traits;

use App\Models\Question;
use App\Models\Thread;
use App\Models\User;
use App\Notifications\Messages\Database\PostMessage;
use App\Notifications\Messages\Wechat\QuestionedWechatMessage;
use App\Notifications\Questioned;
use App\Notifications\System;
use Illuminate\Support\Arr;

/**
 * Thread 发送通知
 * Trait ThreadNoticesTrait
 *
 * @package App\Traits
 */
trait ThreadNoticesTrait
{
    /**
     * 发送通知
     *
     * @param Thread $thread
     * @param User $actor
     * @param string $type
     * @param string $message
     */
    public function threadNotices(Thread $thread, User $actor, $type, $message = '')
    {
        // 审核通过时发送 @ 通知
        if ($type === 'isApproved' && $thread->is_approved === Thread::APPROVED) {
            $this->sendRelated($thread->firstPost, $thread->user);

            // 问答帖审核通过时，通知被提问者
            if ($thread->type === Thread::TYPE_OF_QUESTION && $thread->question) {
                $this->sendQuestioned($thread->question, $thread->user);
            }
        }

        // 无需给自己发送通知
        if ($thread->user_id == $actor->id) {
            return;
        }

        $message = $message ?: '无';

        switch ($type) {
            case 'isEssence':   // 内容加精通知
                $this->sendIsEssence($thread);
                break;
            case 'isSticky':    // 内容置顶通知
                $this->sendIsSticky($thread);
                break;
            case 'isApproved':  // 内容审核通知
                $this->sendIsApproved($thread, ['refuse' => $message]);
                break;
            case 'isDeleted':   // 内容删除通知
                $this->sendIsDeleted($thread, ['refuse' => $message]);
                break;
        }
    }

    /**
     * @param Question $question
     * @param User $user 主题创建人
     */
    public function sendQuestioned(Question $question, User $user)
    {
        // 帖子合法才允许发送
        $build = [
            'message' => $question->thread->getContentByType(Thread::CONTENT_LENGTH, true),
            'raw' => array_merge(Arr::only($question->toArray(), ['thread_id', 'price']), [
                'actor_username' => $question->thread->isAnonymousName(),   // 提问人姓名/匿名
            ]),
        ];

        // Tag 发送通知 (向回答人发送问答通知)
        $question->beUser->notify(new Questioned(QuestionedWechatMessage::class, $user, $question, $build));
    }

    /**
     * 内容置顶通知
     *
     * @param $thread
     */
    private function sendIsSticky($thread)
    {
        $build = [
            'message' => $this->getThreadTitle($thread),
            'raw' => ['thread_id' => $thread->id],
            'notify_type' => PostMessage::NOTIFY_STICKY_TYPE,
        ];

        // Tag 发送通知
        $thread->user->notify(new System(PostMessage::class, $thread->user, $build));
    }

    /**
     * 内容精华通知
     *
     * @param $thread
     */
    private function sendIsEssence($thread)
    {
        $build = [
            'message' => $this->getThreadTitle($thread),
            'raw' => ['thread_id' => $thread->id],
            'notify_type' => PostMessage::NOTIFY_ESSENCE_TYPE,
        ];

        // Tag 发送通知
        $thread->user->notify(new System(PostMessage::class, $thread->user, $build));
    }

    /**
     * 内容删除通知
     *
     * @param $thread
     * @param array $attach 原因
     */
    private function sendIsDeleted($thread, array $attach)
    {
        $data = [
            'message' => $this->getThreadTitle($thread),
            'refuse' => $attach['refuse'],
            'raw' => ['thread_id' => $thread->id],
            'notify_type' => PostMessage::NOTIFY_DELETE_TYPE,
        ];

        // Tag 发送通知
        $thread->user->notify(new System(PostMessage::class, $thread->user, $data));
    }

    /**
     * 内容审核通知
     *
     * @param $thread
     * @param array $attach 原因
     */
    private function sendIsApproved($thread, array $attach)
    {
        $data = [
            'message' => $this->getThreadTitle($thread),
            'refuse' => $attach['refuse'],
            'raw' => ['thread_id' => $thread->id],
        ];

        if ($thread->is_approved == 1) {
            // 发送通过通知
            $data = array_merge($data, ['notify_type' => PostMessage::NOTIFY_APPROVED_TYPE]);
        } elseif ($thread->is_approved == 2) {
            // 忽略就发送不通过通知
            $data = array_merge($data, ['notify_type' => PostMessage::NOTIFY_UNAPPROVED_TYPE]);
        }

        // Tag 发送通知
        $thread->user->notify(new System(PostMessage::class, $thread->user, $data));
    }

    /**
     * 首贴内容代替
     *
     * @param $thread
     * @return mixed
     */
    public function getThreadTitle($thread)
    {
        return empty($thread->title) ? $thread->firstPost->content : $thread->title;
    }
}
