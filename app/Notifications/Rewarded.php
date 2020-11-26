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

use App\Models\User;
use App\Notifications\Messages\Database\RewardedMessage;
use App\Notifications\Messages\Wechat\ExpiredWechatMessage;
use App\Notifications\Messages\Wechat\RewardedScaleWechatMessage;
use App\Notifications\Messages\Wechat\RewardedWechatMessage;
use Discuz\Notifications\Messages\SimpleMessage;
use Discuz\Notifications\NotificationManager;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * 支付通知
 *
 * @package App\Notifications
 */
class Rewarded extends AbstractNotification
{
    public $user;

    public $model;

    public $data;

    protected $message;

    public $tplId = [];

    /**
     * @var Collection
     */
    protected $messageRelationship;

    public function __construct($message, User $user, Model $model, $data = [])
    {
        $this->message = app($message);

        $this->user = $user;
        $this->model = $model;
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
        $message->setData($this->getTplModel('database'), $this->user, $this->model, $this->data);

        return (new NotificationManager)->driver('database')->setNotification($message)->build();
    }

    public function toWechat($notifiable)
    {
        $message = $this->getMessage('wechat');
        $message->setData($this->getTplModel('wechat'), $this->user, $this->model, $this->data);

        return (new NotificationManager)->driver('wechat')->setNotification($message)->build();
    }

    protected function initNoticeMessage()
    {
        /**
         * init database message
         */
        $this->messageRelationship = collect();
        $this->messageRelationship['wechat'] = $this->message;

        // set public database message relationship
        $this->messageRelationship['database'] = app(RewardedMessage::class);

        /**
         * set tpl id
         */
        if ($this->message instanceof RewardedWechatMessage) {
            // 内容支付通知
            $this->tplId['database'] = 27;
            $this->data = array_merge($this->data, ['notice_types_of' => 1]); // 收入通知
        }
        if ($this->message instanceof RewardedScaleWechatMessage) {
            // 内容支付分成通知
            $this->tplId['database'] = 37;
            // 分成通知
            $this->data = array_merge($this->data, [
                'notice_types_of' => 2,
                'is_scale_class' => true, // 是否是分成通知类
            ]);
        }
        if ($this->message instanceof ExpiredWechatMessage) {
            // 打赏过期通知
            $this->tplId['database'] = 43;
            $this->data = array_merge($this->data, ['notice_types_of' => 3]); // 过期通知
        }
        // 31 内容支付通知  38 内容支付分成通知  44 过期通知
        $this->tplId['wechat'] = $this->messageRelationship['wechat']->tplId;
    }
}
