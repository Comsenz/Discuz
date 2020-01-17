<?php


namespace App\MessageTemplate;


use Discuz\Notifications\Messages\DatabaseMessage;
use Illuminate\Support\Str;

class PostMessage extends DatabaseMessage
{
    protected function getTitle() {
        return '内容修改通知';
    }

    protected function getContent($data)
    {
        return sprintf('【%s】你好，你的发布的内容" 【%s】 " 已被修改', $this->notifiable->username, Str::words($data['message'], 10));
    }
}
