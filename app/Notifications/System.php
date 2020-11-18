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

use App\Models\User;
use App\Notifications\Messages\Database\GroupMessage;
use App\Notifications\Messages\Database\PostMessage;
use App\Notifications\Messages\Database\RegisterMessage;
use App\Notifications\Messages\Database\StatusMessage;
use App\Notifications\Messages\Wechat\GroupWechatMessage;
use App\Notifications\Messages\Wechat\PostWechatMessage;
use App\Notifications\Messages\Wechat\RegisterWechatMessage;
use App\Notifications\Messages\Wechat\StatusWechatMessage;
use Discuz\Notifications\Messages\SimpleMessage;
use Discuz\Notifications\NotificationManager;
use Exception;
use Illuminate\Support\Collection;

class System extends AbstractNotification
{
    public $actor;

    public $data;

    protected $message;

    public $tplId = [];

    /**
     * @var Collection
     */
    protected $messageRelationship;

    public function __construct($message, User $actor, $data = [])
    {
        $this->message = app($message);

        $this->actor = $actor;
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
        $message->setData($this->getTplModel('database'), $this->actor, $this->data);

        return (new NotificationManager)->driver('database')->setNotification($message)->build();
    }

    public function toWechat($notifiable)
    {
        $message = $this->getMessage('wechat');
        $message->setData($this->getTplModel('wechat'), $this->actor, $this->data);

        return (new NotificationManager)->driver('wechat')->setNotification($message)->build();
    }

    /**
     * 初始化对应通知类型
     * TODO 尽量拆分通知为独立通知 Message，该方法最好不再叠加新通知类型（通知列表接口查询时传输类型数组筛选，就可做到每种通知的独立性）
     */
    protected function initNoticeMessage()
    {
        $this->messageRelationship = collect();
        // init database message
        $this->messageRelationship['database'] = $this->message;

        // 用户状态通知
        if ($this->message instanceof StatusMessage) {
            // set other message relationship
            $this->messageRelationship['wechat'] = app(StatusWechatMessage::class);
            // set tpl id
            $this->discTpl($this->actor->status, $this->actor->getRawOriginal('status'));
        }

        // 用户组变更通知
        if ($this->message instanceof GroupMessage) {
            // set other message relationship
            $this->messageRelationship['wechat'] = app(GroupWechatMessage::class);
            // set tpl id
            $this->tplId['database'] = $this->messageRelationship['database']->tplId; // 12
            $this->tplId['wechat'] = $this->messageRelationship['wechat']->tplId; // 24
        }

        // Post 通知
        if ($this->message instanceof PostMessage) {
            // set other message relationship
            $this->messageRelationship['wechat'] = app(PostWechatMessage::class);
            // set tpl id of the notify type
            $this->postTpl();
        }

        // 注册通知
        if ($this->message instanceof RegisterMessage || $this->message instanceof RegisterWechatMessage) {
            // 分别发送通知类型，因为注册时有未绑定公众号的用户，获取不到 openId
            if (! isset($this->data['send_type'])) {
                return;
            }
            // set other message relationship
            if ($this->message instanceof RegisterWechatMessage) {
                $this->messageRelationship['wechat'] = app(RegisterWechatMessage::class);
            }
            // set tpl id
            $sendType = $this->data['send_type'];
            if (! is_null($sendType)) {
                $this->tplId[$sendType] = $this->messageRelationship[$sendType]->tplId; // 1 数据库通知 / 13 微信通知
            }
        }
    }

    /**
     * 区分通知
     * (审核中变为正常 和 禁用中变为正常)
     *
     * @param $status
     * @param $originStatus
     */
    public function discTpl($status, $originStatus)
    {
        if ($status == $originStatus) {
            return;
        }

        if ($status == 0) {
            if ($originStatus == 1) {
                // 帐号解除禁用通知
                $this->tplId = [
                    'database' => 11,
                    'wechat' => 23,
                ];
            } else {
                // 审核通过通知
                $this->tplId = [
                    'database' => 2,
                    'wechat' => 14,
                ];
            }
        } else {
            if ($originStatus == 0 && $status == 1) {
                // 帐号禁用通知
                $this->tplId = [
                    'database' => 10,
                    'wechat' => 22,
                ];
            } elseif ($originStatus == 2 && $status == 3) { // 2审核中 变 审核拒绝
                // 审核拒绝通知
                $this->tplId = [
                    'database' => 3,
                    'wechat' => 15,
                ];
            } else {
                // 错误状态下：2审核中变成禁用等 是不允许的
                return;
            }
        }
    }

    /**
     * Post 类型通知
     */
    public function postTpl()
    {
        /**
         * Post 类型通知扩展参数 必传操作类型
         *
         * @see PostMessage 新参数用常量去预设，消除魔术字符串
         */
        if (! isset($this->data['notify_type'])) {
            throw new Exception('not_found_post_notify_type');
        }

        switch ($this->data['notify_type']) {
            case PostMessage::NOTIFY_EDIT_CONTENT_TYPE:
                // 内容修改通知
                $this->tplId = [
                    'database' => 9,
                    'wechat' => 21,
                ];
                break;
            case PostMessage::NOTIFY_APPROVED_TYPE:
                // 内容审核通过通知
                $this->tplId = [
                    'database' => 5,
                    'wechat' => 16,
                ];
                break;
            case PostMessage::NOTIFY_UNAPPROVED_TYPE:
                // 内容审核不通过/内容忽略 通知
                $this->tplId = [
                    'database' => 4,
                    'wechat' => 17,
                ];
                break;
            case PostMessage::NOTIFY_DELETE_TYPE:
                // 内容删除通知
                $this->tplId = [
                    'database' => 6,
                    'wechat' => 18,
                ];
                break;
            case PostMessage::NOTIFY_ESSENCE_TYPE:
                // 内容精华通知
                $this->tplId = [
                    'database' => 7,
                    'wechat' => 19,
                ];
                break;
            case PostMessage::NOTIFY_STICKY_TYPE:
                // 内容置顶通知
                $this->tplId = [
                    'database' => 8,
                    'wechat' => 20,
                ];
                break;
            default:
                throw new Exception('post_notify_type_mismatch');
        }
    }
}
