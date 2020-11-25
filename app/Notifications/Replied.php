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

namespace App\Notifications;

use App\Models\Post;
use App\Models\User;
use App\Notifications\Messages\Database\RepliedMessage;
use App\Notifications\Messages\Wechat\RepliedWechatMessage;
use Discuz\Notifications\NotificationManager;

/**
 * 回复通知
 *
 * @package App\Notifications
 */
class Replied extends AbstractNotification
{
    public $actor;

    public $post;

    public $data;

    public $tplId = [
        'database' => 25,
        'wechat' => 29,
    ];

    public function __construct(User $actor, Post $post, $data = [])
    {
        $this->setTemplate();

        $this->actor = $actor;
        $this->post = $post;
        $this->data = $data;
    }

    /**
     * 设置所有开启中的，要发送的模板
     * 查询到数据集合后，存放静态区域
     */
    protected function setTemplate()
    {
        self::getTemplate($this->tplId);
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        // 获取已开启的通知频道
        return $this->getNotificationChannels();
    }

    public function getTplModel($type)
    {
        return self::$tplData->where('id', $this->tplId[$type])->first();
    }

    public function toDatabase($notifiable)
    {
        $message = app(RepliedMessage::class);
        $message->setData($this->getTplModel('database'), $this->actor, $this->post);

        return (new NotificationManager)->driver('database')->setNotification($message)->build();
    }

    public function toWechat($notifiable)
    {
        $message = app(RepliedWechatMessage::class);
        $message->setData($this->getTplModel('wechat'), $this->actor, $this->post, $this->data);

        return (new NotificationManager)->driver('wechat')->setNotification($message)->build();
    }

}
