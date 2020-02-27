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
use App\Notifications\System;
use Illuminate\Support\Arr;

trait ThreadNoticesTrait
{
    /**
     * 内容置顶通知
     *
     * @param $thread
     */
    public function sendIsSticky($thread)
    {
        $thread->user->notify(new System(PostOrderMessage::class, [
            'message' => $this->getThreadTitle($thread),
            'raw' => [
                'thread_id' => $thread->id,
            ],
        ]));
    }

    /**
     * 内容精华通知
     *
     * @param $thread
     */
    public function sendIsEssence($thread)
    {
        $thread->user->notify(new System(PostStickMessage::class, [
            'message' => $this->getThreadTitle($thread),
            'raw' => [
                'thread_id' => $thread->id,
            ],
        ]));
    }

    /**
     * 内容删除通知
     *
     * @param $thread
     * @param array $attach 原因
     */
    public function sendIsDeleted($thread, $attach = [])
    {
        $data = [
            'message' => $this->getThreadTitle($thread),
            'refuse' => Arr::get($attach, 'refuse', ''),
            'raw' => [
                'thread_id' => $thread->id,
            ],
        ];
        $thread->user->notify(new System(PostDeleteMessage::class, $data));
    }

    /**
     * 内容审核通知
     *
     * @param $thread
     * @param array $attach 原因
     */
    public function sendIsApproved($thread, $attach = [])
    {
        $data = [
            'message' => $this->getThreadTitle($thread),
            'refuse' => Arr::get($attach, 'refuse', ''),
            'raw' => [
                'thread_id' => $thread->id,
            ],
        ];
        $thread->user->notify(new System(PostModMessage::class, $data));
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
