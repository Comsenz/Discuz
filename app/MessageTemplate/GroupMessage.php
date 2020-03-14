<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\MessageTemplate;

use Discuz\Notifications\Messages\DatabaseMessage;

/**
 * 用户角色调整通知
 *
 * Class GroupMessage
 * @package App\MessageTemplate
 */
class GroupMessage extends DatabaseMessage
{
    protected $tplId = 12;

    protected function titleReplaceVars()
    {
        return [];
    }

    protected function contentReplaceVars($data)
    {
        $oldGroup = $data['old'];
        $newGroup = $data['new'];

        return [
            $this->notifiable->username,
            $oldGroup->pluck('name')->join('、'),
            $newGroup->pluck('name')->join('、')
        ];
    }
}
