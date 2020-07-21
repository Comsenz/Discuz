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

use App\MessageTemplate\PostDeleteMessage;
use App\MessageTemplate\PostModMessage;
use App\MessageTemplate\PostOrderMessage;
use App\MessageTemplate\PostStickMessage;
use App\MessageTemplate\PostThroughMessage;
use App\MessageTemplate\Wechat\WechatPostDeleteMessage;
use App\MessageTemplate\Wechat\WechatPostModMessage;
use App\MessageTemplate\Wechat\WechatPostOrderMessage;
use App\MessageTemplate\Wechat\WechatPostStickMessage;
use App\MessageTemplate\Wechat\WechatPostThroughMessage;
use App\Models\Thread;
use App\Models\User;
use App\Notifications\System;
use Illuminate\Support\Arr;

/**
 * Thread 发送通知
 *
 * Trait ThreadNoticesTrait
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
     * 内容置顶通知
     *
     * @param $thread
     */
    private function sendIsSticky($thread)
    {
        $build = [
            'message' => $this->getThreadTitle($thread),
            'raw' => [
                'thread_id' => $thread->id,
            ],
        ];

        // 系统通知
        $thread->user->notify(new System(PostOrderMessage::class, $build));

        // 微信通知
        $thread->user->notify(new System(WechatPostOrderMessage::class, $build));
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
            'raw' => [
                'thread_id' => $thread->id,
            ],
        ];

        // 系统通知
        $thread->user->notify(new System(PostStickMessage::class, $build));

        // 微信通知
        $thread->user->notify(new System(WechatPostStickMessage::class, $build));
    }

    /**
     * 内容删除通知
     *
     * @param $thread
     * @param array $attach 原因
     */
    private function sendIsDeleted($thread, $attach)
    {
        $data = [
            'message' => $this->getThreadTitle($thread),
            'refuse' => $attach['refuse'],
        ];

        // 系统通知
        $thread->user->notify(new System(PostDeleteMessage::class, Arr::set($data, 'raw', ['thread_id' => $thread->id])));

        // 微信通知 (跳转到首页)
        $thread->user->notify(new System(WechatPostDeleteMessage::class, Arr::set($data, 'raw', ['thread_id' => 0])));
    }

    /**
     * 内容审核通知
     *
     * @param $thread
     * @param array $attach 原因
     */
    private function sendIsApproved($thread, $attach)
    {
        $data = [
            'message' => $this->getThreadTitle($thread),
            'refuse' => $attach['refuse'],
            'raw' => [
                'thread_id' => $thread->id,
            ],
        ];

        if ($thread->is_approved == 1) {
            // 发送通过通知
            $thread->user->notify(new System(PostThroughMessage::class, $data));
            // 微信通知
            $thread->user->notify(new System(WechatPostThroughMessage::class, $data));
        } elseif ($thread->is_approved == 2) {
            // 忽略就发送不通过通知
            $thread->user->notify(new System(PostModMessage::class, $data));
            // 微信通知
            $thread->user->notify(new System(WechatPostModMessage::class, $data));
        }
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
