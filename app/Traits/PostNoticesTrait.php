<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Traits;

use App\MessageTemplate\PostDeleteMessage;
use App\MessageTemplate\PostModMessage;
use App\MessageTemplate\PostOrderMessage;
use App\MessageTemplate\PostStickMessage;
use App\MessageTemplate\PostThroughMessage;
use App\MessageTemplate\Wechat\WechatPostDeleteMessage;
use App\MessageTemplate\Wechat\WechatPostModMessage;
use App\MessageTemplate\Wechat\WechatPostThroughMessage;
use App\Models\Post;
use App\Notifications\System;
use Illuminate\Support\Arr;

/**
 * Post 发送通知
 *
 * Trait PostNoticesTrait
 * @package App\Traits
 */
trait PostNoticesTrait
{
    /**
     * 内容删除通知
     *
     * @param $post
     * @param array $attach 原因
     */
    private function postIsDeleted($post, $attach)
    {
        $data = [
            'message' => $this->getPostTitle($post),
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
        return $post->thread->type == 1 ? $post->thread->title : $post->formatContent();
    }

    /**
     * 过滤原因值
     *
     * @param $attach
     * @return mixed|string
     */
    public function reasonValue($attach)
    {
        if (Arr::has($attach, 'refuse')) {
            if (!empty($attach['refuse'])) {
                return $attach['refuse'];
            }
        }

        return '无';
    }
}
