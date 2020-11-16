<?php

/**
 * Copyright (C) 2020 Tencent Cloud.
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *   http://www.apache.org/licenses/LICENSE-2.0
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace App\Notifications;

use App\Models\Question;
use App\Models\User;
use App\Notifications\Messages\Database\QuestionedMessage;
use App\Notifications\Messages\Wechat\QuestionedAnswerWechatMessage;
use App\Notifications\Messages\Wechat\QuestionedWechatMessage;
use Discuz\Notifications\Messages\SimpleMessage;
use Discuz\Notifications\NotificationManager;
use Illuminate\Support\Collection;

/**
 * 问答通知
 *
 * @package App\Notifications
 */
class Questioned extends AbstractNotification
{
    public $user;

    public $question;

    public $data;

    protected $message;

    public $tplId = [];

    /**
     * @var Collection
     */
    protected $messageRelationship;

    public function __construct($message, User $user, Question $question, $data = [])
    {
        $this->message = app($message);

        // 提问人 / 被提问人
        $this->user = $user;
        $this->question = $question;
        $this->data = $data;

        /**
         * 初始化要发送的模板中，对应的 tplId
         */
        $this->initNoticeMessage();

        $this->setTemplate();
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

    /**
     * @param string $type
     * @return SimpleMessage
     */
    public function getMessage(string $type)
    {
        return $this->messageRelationship->get($type);
    }

    public function toDatabase($notifiable)
    {
        $message = $this->getMessage('database');
        $message->setData($this->getTplModel('database'), $this->user, $this->question);

        return (new NotificationManager)->driver('database')->setNotification($message)->build();
    }

    public function toWechat($notifiable)
    {
        $message = $this->getMessage('wechat');
        $message->setData($this->getTplModel('wechat'), $this->user, $this->question, $this->data);

        return (new NotificationManager)->driver('wechat')->setNotification($message)->build();
    }

    protected function initNoticeMessage()
    {
        /**
         * init database message
         *
         * @see QuestionedWechatMessage 给回答人发送微信通知
         * @see QuestionedAnswerWechatMessage 给提问人发送微信通知
         */
        $this->messageRelationship = collect();
        $this->messageRelationship['wechat'] = $this->message;

        // set public database message relationship
        $this->messageRelationship['database'] = app(QuestionedMessage::class);

        // set tpl id
        if ($this->message instanceof QuestionedWechatMessage) {
            $this->tplId['database'] = 39;
        } elseif ($this->message instanceof QuestionedAnswerWechatMessage) {
            $this->tplId['database'] = 41;
        }
        $this->tplId['wechat'] = $this->messageRelationship['wechat']->tplId; // 40 、 42
    }
}
