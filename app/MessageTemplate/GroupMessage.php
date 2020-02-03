<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\MessageTemplate;

use Discuz\Notifications\Messages\DatabaseMessage;

class GroupMessage extends DatabaseMessage
{
    protected function getTitle()
    {
        return '用户组调整通知';
    }

    protected function getContent($data)
    {
        $oldGroup = $data['old'];
        $newGroup = $data['new'];

        return sprintf(
            '【%s】你好，你的角色由【%s】变更为【%s】',
            $this->notifiable->username,
            $oldGroup->pluck('name')->join('、'),
            $newGroup->pluck('name')->join('、')
        );
    }
}
