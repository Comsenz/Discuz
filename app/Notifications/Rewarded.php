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
use App\Models\Thread;
use Illuminate\Bus\Queueable;

/**
 * 支付通知
 * (包含: 打赏帖子/支付付费贴)
 *
 * Class Rewarded
 * @package App\Notifications
 */
class Rewarded extends System
{
    use Queueable;

    public $order;

    public $actor;

    public $channel;

    /**
     * @var bool 是否是分成通知类
     */
    public $isScaleClass;

    /**
     * Rewarded constructor.
     *
     * @param Order $order
     * @param $actor
     * @param string $messageClass
     * @param array $build
     */
    public function __construct(Order $order, $actor, $messageClass = '', $build = [])
    {
        $this->setChannelName($messageClass);

        $this->order = $order;
        $this->actor = $actor;

        parent::__construct($messageClass, $build);
    }

    /**
     * 数据库驱动通知
     *
     * @param $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        /**
         * 判断是否是分成通知，上级金额/自己收款金额 不同
         */
        if ($this->isScaleClass) {
            // 分成通知数据
            $build = [
                'user_id' => $this->order->user->id,
                'order_id' => $this->order->id,
                'thread_id' => 0,  // 注册无主题关联
                'thread_username' => 0,
                'thread_title' => 0,
                'content' => '',
                'thread_created_at' => '',
                'amount' => $this->order->calculateAuthorAmount(), // 获取上级的实际分成金额数
                'order_type' => $this->order->type,  // 1：注册，2：打赏，3：付费主题，4：付费用户组
            ];
            // 判断如果是 打赏/付费有主题 类型
            if (in_array($this->order->type, [2, 3])) {
                $build['thread_id'] = $this->order->thread->id;
                $build['thread_username'] = $this->order->thread->user->username;
                $build['thread_title'] = $this->order->thread->title;
                $this->build($build);
            }
        } else {
            $build = [
                'user_id' => $this->order->user->id,  // 付款人ID
                'order_id' => $this->order->id,
                'thread_id' => $this->order->thread->id,   // 必传
                'thread_username' => $this->order->thread->user->username, // 必传主题用户名
                'thread_title' => $this->order->thread->title,
                'content' => '',  // 兼容原数据
                'thread_created_at' => $this->order->thread->formatDate('created_at'),
                'amount' => $this->order->calculateAuthorAmount(true), // 支付金额 - 分成金额 (string精度问题)
                'order_type' => $this->order->type,  // 1：注册，2：打赏，3：付费主题，4：付费用户组
            ];

            $this->build($build);
        }

        // 是否是分成金额
        $build = array_merge($build, ['isScale' => $this->order->isScale()]);

        return $build;
    }

    /**
     * @param $build
     */
    public function build(&$build)
    {
        $content = $this->order->thread->getContentByType(Thread::CONTENT_LENGTH);

        $build['content'] = $content;
    }

    /**
     * 设置驱动名称&属性
     *
     * @param $strClass
     */
    protected function setChannelName($strClass)
    {
        switch ($strClass) {
            case 'App\MessageTemplate\Wechat\WechatRewardedMessage':
                $this->channel = 'wechat';
                $this->isScaleClass = false;
                break;
            case 'App\MessageTemplate\Wechat\WechatRewardedScaleMessage':
                $this->channel = 'wechat';
                $this->isScaleClass = true;
                break;
            case 'App\MessageTemplate\RewardedMessage':
            default:
                $this->channel = 'database';
                $this->isScaleClass = false;
                break;
            case 'App\MessageTemplate\RewardedScaleMessage':
                $this->channel = 'database';
                $this->isScaleClass = true;
                break;
        }
    }
}
