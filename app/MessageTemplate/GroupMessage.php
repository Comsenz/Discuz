<?php


namespace App\MessageTemplate;


use Discuz\Notifications\Messages\DatabaseMessage;

class GroupMessage extends DatabaseMessage
{

    protected function getTitle() {
        return '角色调整通知';
    }

    protected function getContent($data)
    {
        $oldGroup = $data['old'];
        $newGroup = $data['new'];

        return sprintf('【%s】你好，你的角色由【%s】变更为【%s】', $this->notifiable->username, $oldGroup->name, $newGroup->name);

    }
}
