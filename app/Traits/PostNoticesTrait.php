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
use App\MessageTemplate\PostThroughMessage;
use App\MessageTemplate\RelatedMessage;
use App\MessageTemplate\RepliedMessage;
use App\MessageTemplate\Wechat\WechatPostDeleteMessage;
use App\MessageTemplate\Wechat\WechatPostModMessage;
use App\MessageTemplate\Wechat\WechatPostThroughMessage;
use App\MessageTemplate\Wechat\WechatRelatedMessage;
use App\MessageTemplate\Wechat\WechatRepliedMessage;
use App\Models\Post;
use App\Models\Thread;
use App\Models\User;
use App\Notifications\Related;
use App\Notifications\Replied;
use App\Notifications\System;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use s9e\TextFormatter\Utils;

/**
 * Post 发送通知
 *
 * Trait PostNoticesTrait
 * @package App\Traits
 */
trait PostNoticesTrait
{
    /**
     * 发送通知
     *
     * @param Post $post
     * @param User $actor
     * @param string $type
     * @param string $message
     */
    public function postNotices(Post $post, User $actor, $type, $message = '')
    {
        // 无需给自己发送通知
        if ($post->user_id == $actor->id) {
            return;
        }

        $message = $message ?: '无';

        switch ($type) {
            case 'isApproved':  // 内容审核通知
                $this->postisapproved($post, ['refuse' => $message]);
                break;
            case 'isDeleted':   // 内容删除通知
                $this->postIsDeleted($post, ['refuse' => $message]);
                break;
        }
    }

    /**
     * 发送@通知
     *
     * @param Post $post
     * @param User $actor 并非当前登录用户,这里是发帖人
     */
    public function sendRelated(Post $post, User $actor)
    {
        $mentioned = Utils::getAttributeValues($post->parsedContent, 'USERMENTION', 'id');

        $post->mentionUsers()->sync($mentioned);

        $users = User::whereIn('id', $mentioned)->get();
        $users->load('deny');
        $users->filter(function ($user) use ($post) {
            //把作者拉黑的用户不发通知
            return !in_array($post->user_id, array_column($user->deny->toArray(), 'id'));
        })->each(function (User $user) use ($post, $actor) {
            // 数据库通知
            $user->notify(new Related($post, $actor, RelatedMessage::class));

            // 微信通知
            $user->notify(new Related($post, $actor, WechatRelatedMessage::class, [
                'message' => $post->getSummaryContent(Post::NOTICE_LENGTH, true)['content'],
                'raw' => array_merge(Arr::only($post->toArray(), ['id', 'thread_id', 'reply_post_id']), [
                    'actor_username' => $actor->username    // 发送人姓名
                ]),
            ]));
        });
    }

    /**
     * 内容删除通知
     *
     * @param $post
     * @param array $attach 原因
     */
    private function postIsDeleted($post, $attach)
    {
        $post->content = Str::of($post->content)->substr(0, Post::NOTICE_LENGTH);
        $post->formatContent();

        $data = [
            'message' => $post->formatContent(), // 解析表情
            'refuse' => $attach['refuse'],
            'raw' => [
                'thread_id' => $post->thread->id,
            ],
        ];

        $post->user->notify(new System(PostDeleteMessage::class, $data));
        $post->user->notify(new System(WechatPostDeleteMessage::class, $data));
    }

    /**
     * 内容审核通知
     *
     * @param $post
     * @param array $attach 原因
     */
    private function postIsApproved($post, $attach)
    {
        $data = [
            'message' => $this->getPostTitle($post),
            'refuse' => $attach['refuse'],
            'raw' => [
                'thread_id' => $post->thread->id,
            ],
        ];

        if ($post->is_approved == 1) {
            // 发送通过通知
            $post->user->notify(new System(PostThroughMessage::class, $data));
            // 发送微信通知
            $post->user->notify(new System(WechatPostThroughMessage::class, $data));
            // 发送回复人的主题通知 (回复自己主题不发送通知)
            if ($post->user_id != $post->thread->user_id) {
                // 发送系统通知
                $post->thread->user->notify(new Replied($post, $post->user, RepliedMessage::class));
                // 发送微信通知
                $post->thread->user->notify(new Replied($post, $post->user, WechatRepliedMessage::class, [
                    'message' => $post->getSummaryContent(Post::NOTICE_LENGTH, true)['content'],
                    'subject' => $post->thread->getContentByType(Thread::CONTENT_LENGTH, true),
                    'raw' => array_merge(Arr::only($post->toArray(), ['id', 'thread_id', 'reply_post_id']), [
                        'actor_username' => $post->user->username    // 发送人姓名
                    ]),
                ]));
            }
        } elseif ($post->is_approved == 2) {
            // 忽略就发送不通过通知
            $post->user->notify(new System(PostModMessage::class, $data));
            // 发送微信通知
            $post->user->notify(new System(WechatPostModMessage::class, $data));
        }
    }

    /**
     * 标题内容替换
     *
     * @param $post
     * @return mixed
     */
    public function getPostTitle(Post $post)
    {
        return $post->thread->type === Thread::TYPE_OF_LONG ? $post->thread->title : $post->formatContent();
    }
}
