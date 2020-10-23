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

namespace App\Listeners\Order;

use App\Events\Order\Updated;
use App\MessageTemplate\RewardedMessage;
use App\MessageTemplate\RewardedScaleMessage;
use App\MessageTemplate\Wechat\WechatRewardedMessage;
use App\MessageTemplate\Wechat\WechatRewardedScaleMessage;
use App\Models\Order;
use App\Models\Thread;
use App\Notifications\Rewarded;
use Illuminate\Support\Arr;

class SendNotifyAfterPaySuccessful
{
    /**
     * 当前订单
     *
     * @var Order
     */
    protected $order;

    protected $build;

    /**
     * 设置公用通知模板数据
     */
    public function setBuild()
    {
        $this->build = [
            'message' => $this->order->thread->getContentByType(Thread::CONTENT_LENGTH, true),
            'raw' => Arr::only($this->order->toArray(), ['id', 'thread_id', 'type']),
        ];
    }

    public function handle(Updated $event)
    {
        $this->order = $event->order;

        // 不发通知：问答的提问没有主题数据；购买用户组没人可收
        if (
            $this->order->type == Order::ORDER_TYPE_QUESTION
            || $this->order->type == Order::ORDER_TYPE_GROUP
        ) {
            return;
        }

        // 判断是否是支付成功后
        if ($this->order->status != Order::ORDER_STATUS_PAID) {
            return;
        }

        // 除了站点注册，其余获取主题信息
        if ($this->order->type != Order::ORDER_TYPE_REGISTER) {
            $this->setBuild();
        }

        switch ($this->order->type) {
            case Order::ORDER_TYPE_REGISTER: // 付费加入站点
                // 发送分成通知
                $this->sendScaleNotice('user');
                break;
            case Order::ORDER_TYPE_REWARD: // 打赏
                $this->build['raw'] = array_merge($this->build['raw'], [
                    'actor_username' => $this->order->user->username,               // 发送人姓名
                    'actual_amount' => $this->order->calculateAuthorAmount(true),   // 获取实际金额
                ]);

                // 通知主题作者
                $this->sendToPayee();

                // 发送分成通知
                $this->sendScaleNotice('payee');
                break;
            case Order::ORDER_TYPE_THREAD: // 付费主题
                $this->build['raw'] = array_merge($this->build['raw'], [
                    'actor_username' => $this->order->user->username,               // 发送人姓名
                    'actual_amount' => $this->order->calculateAuthorAmount(true),   // 获取实际金额
                ]);

                // 通知作者收款通知
                $this->sendToPayee();

                // 发送分成通知
                $this->sendScaleNotice('payee');
                break;
            case Order::ORDER_TYPE_ONLOOKER: // 围观
                $this->build['raw'] = array_merge($this->build['raw'], [
                    'actor_username' => $this->order->user->username,                   // 发送人姓名
                    'actual_amount' => $this->order->calculateOnlookersAmount(false),   // 获取实际围观分红金额
                ]);

                // 发送给 问答人 收入分成通知 Tag 目前该用户上级不分成
                $this->sendToPayee();

                // 发送给 答题人（第三方用户） 收入分成通知 Tag 目前该用户上级不分成
                $this->sendToThirdParty();
                break;
            case Order::ORDER_TYPE_ATTACHMENT: // 附件付费
                $this->build['raw'] = array_merge($this->build['raw'], [
                    'actor_username' => $this->order->user->username,
                    'actual_amount' => $this->order->calculateAuthorAmount(true),
                ]);

                // 通知作者收款通知
                $this->sendToPayee();

                // 发送分成通知
                $this->sendScaleNotice('payee');
                break;
            default:
                break;
        }
    }

    /**
     * 给收款人发送通知
     */
    public function sendToPayee()
    {
        $this->order->payee->notify(new Rewarded($this->order, $this->order->user, RewardedMessage::class));
        $this->order->payee->notify(new Rewarded($this->order, $this->order->user, WechatRewardedMessage::class, $this->build));
    }

    /**
     * 给第三方发送通知
     */
    public function sendToThirdParty()
    {
        $this->order->thirdParty->notify(new Rewarded($this->order, $this->order->user, RewardedMessage::class));
        $this->order->thirdParty->notify(new Rewarded($this->order, $this->order->user, WechatRewardedMessage::class, $this->build));
    }

    /**
     * 共用发送分成通知
     *
     * @param bool $type payee 打赏/付费  user 注册
     */
    public function sendScaleNotice($type)
    {
        /**
         * 发送分成收入通知
         */
        if ($this->order->isScale()) {
            // 判断是发给 收款人/付款人 的上级
            $userDistribution = $type == 'payee' ? $this->order->payee->userDistribution : $this->order->user->userDistribution;
            if (! empty($userDistribution)) {
                $parentUser = $userDistribution->parentUser;
                $parentUser->notify(new Rewarded($this->order, $this->order->user, RewardedScaleMessage::class));
                $parentUser->notify(new Rewarded($this->order, $this->order->user, WechatRewardedScaleMessage::class, [
                    'message' => $type == 'payee' ? $this->order->thread->getContentByType(Thread::CONTENT_LENGTH, true) : '注册站点',
                    'raw' => array_merge(Arr::only($this->order->toArray(), ['id', 'thread_id', 'type']), [
                        'actor_username' => $this->order->user->username,        // 发送人姓名
                        'boss_amount' => $this->order->calculateAuthorAmount(),  // 获取实际金额
                    ]),
                ]));
            }
        }
    }
}
