<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\MessageTemplate;

use Discuz\Notifications\Messages\DatabaseMessage;
use Illuminate\Support\Arr;

class StatusMessage extends DatabaseMessage
{
    protected function titleReplaceVars()
    {
        return [];
    }

    protected function contentReplaceVars($data)
    {
        return [
            $this->notifiable->username,
            Arr::get($data, 'refuse', '')
        ];
    }


}
