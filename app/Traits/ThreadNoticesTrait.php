<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Traits;

use App\MessageTemplate\PostOrderMessage;
use App\Notifications\System;

trait ThreadNoticesTrait
{
    public function sendIsSticky($thread)
    {
        $thread->user->notify(new System(PostOrderMessage::class, ['message' => $thread]));
    }
}
