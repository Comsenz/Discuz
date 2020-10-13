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

use App\Models\Order;
use App\Models\Question;
use App\Models\Thread;
use Illuminate\Bus\Queueable;
use Illuminate\Database\Eloquent\Model;

/**
 * 支付通知
 * (包含: 打赏帖子/支付付费贴)
 *
 * Class Rewarded
 *
 * @package App\Notifications
 */
class Rewarded extends System
{
    use Queueable;

    public $actor;

    public $channel;

    /**
     * @var string 通知类型
     */
    public $noticeType;

    /**
     * @var array 通知模型数据
     */
    public $initData;

    /**
     * @var Order
     */
    public $order = null;

    /**
     * @var Question
     */
    public $question = null;

    /**
     * @var bool 是否是分成通知类
     */
    public $isScaleClass;

    /**
     * Rewarded constructor.
     *
     * @param Model $model Question|Order
     * @param $actor
     * @param string $messageClass
     * @param array $build
     */
    public function __construct(Model $model, $actor, $messageClass = '', $build = [])
    {
        $this->setModel($model);

        $this->setChannelName($messageClass);

        $this->initData();

        parent::__construct($messageClass, $build);
    }

    public function setModel($model)
    {
        if ($model instanceof Order) {
            $this->order = $model;
        } elseif ($model instanceof Question) {
            $this->question = $model;
        }
    }

    public function initData()
    {
        $this->initData = [
            'user_id' => 0,
            'order_id' => 0,    // 订单 id
            'thread_id' => 0,   // 必传 可为0 主题关联 id
            'thread_username' => 0,
            'thread_title' => 0,
            'content' => '',
            'thread_created_at' => '',
            'amount' => 0, // 获取上级的实际分成金额数
            'order_type' => 0,  // 1注册 2打赏 3付费主题 4付费用户组
            'notice_type' => $this->noticeType, // 1收入通知 2分成通知 3过期通知
        ];
    }

    /**
     * 数据库驱动通知
     *
     * @param $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        if (! is_null($this->order)) {
            $this->noticeByOrder();
        } elseif (! is_null($this->question)) {
            $this->noticeByQuestion();
        }

        return $this->initData;
    }

    /**
     * 当存在订单时，发送 收入/分成 通知
     */
    public function noticeByOrder()
    {
        $this->initData['user_id'] = $this->order->user->id; // 付款人ID
        $this->initData['order_id'] = $this->order->id;
        $this->initData['order_type'] = $this->order->type; // 1：注册，2：打赏，3：付费主题，4：付费用户组

        /**
         * 判断是否是分成通知，上级金额/自己收款金额 不同
         */
        if ($this->isScaleClass) {
            // 分成通知数据
            $this->initData['amount'] = $this->order->calculateAuthorAmount(); // 获取上级的实际分成金额数
            // 判断如果是 打赏/付费有主题 类型
            if (in_array($this->order->type, [2, 3])) {
                $this->initData['thread_id'] = $this->order->thread->id;
                $this->initData['thread_username'] = $this->order->thread->user->username;
                $this->initData['thread_title'] = $this->order->thread->title;
                $this->build();
            }
        } else {
            $this->initData['thread_id'] = $this->order->thread->id; // 必传
            $this->initData['thread_username'] = $this->order->thread->user->username; // 必传主题用户名
            $this->initData['thread_title'] = $this->order->thread->title;
            $this->initData['thread_created_at'] = $this->order->thread->formatDate('created_at');
            if ($this->order->type == Order::ORDER_TYPE_ONLOOKER) {
                // 获取实际围观分红金额
                $this->initData['amount'] = $this->order->calculateOnlookersAmount(false);
            } else {
                $this->initData['amount'] = $this->order->calculateAuthorAmount(true); // 支付金额 - 分成金额 (string精度问题)
            }
            $this->build();
        }

        // 当有订单时 必传 是否是分成金额
        $this->initData['isScale'] = $this->order->isScale();
    }

    /**
     * 赋值内容
     */
    public function build()
    {
        $content = '';

        if (! is_null($this->order)) {
            $content = $this->order->thread->getContentByType(Thread::CONTENT_LENGTH);
        } elseif (! is_null($this->question)) {
            $content = $this->question->thread->getContentByType(Thread::CONTENT_LENGTH);
        }

        $this->initData['content'] = $content;
    }

    /**
     * 当模型是问答时，根据类型发送 解冻/冻结 通知
     */
    public function noticeByQuestion()
    {
        $this->initData['user_id'] = $this->question->user->id; // 解冻退回用户
        $this->initData['thread_id'] = $this->question->thread->id;
        $this->initData['thread_username'] = $this->question->thread->user->username;
        $this->initData['thread_title'] = $this->question->thread->title;
        $this->initData['thread_created_at'] = $this->question->thread->formatDate('created_at');
        $this->initData['amount'] = $this->question->price; // 解冻退还金额
        $this->build();
    }

    /**
     * 设置频道名称&属性
     *
     * @param $strClass
     */
    protected function setChannelName($strClass)
    {
        switch ($strClass) {
            case 'App\MessageTemplate\RewardedMessage':
                $this->channel = 'database';
                $this->isScaleClass = false;
                $this->noticeType = 1; // 收入通知
                break;
            case 'App\MessageTemplate\Wechat\WechatRewardedMessage':
                $this->channel = 'wechat';
                $this->isScaleClass = false;
                $this->noticeType = 1;
                break;
            case 'App\MessageTemplate\RewardedScaleMessage':
                $this->channel = 'database';
                $this->isScaleClass = true;
                $this->noticeType = 2; // 分成通知
                break;
            case 'App\MessageTemplate\Wechat\WechatRewardedScaleMessage':
                $this->channel = 'wechat';
                $this->isScaleClass = true;
                $this->noticeType = 2;
                break;
            case 'App\MessageTemplate\ExpiredMessage':
                $this->channel = 'database';
                $this->noticeType = 3; // 过期通知
                break;
            case 'App\MessageTemplate\Wechat\WechatExpiredMessage':
                $this->channel = 'wechat';
                $this->noticeType = 3;
                break;
        }
    }
}
