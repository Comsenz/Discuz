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

namespace App\Listeners\Wallet;

use App\Events\Wallet\Saved;
use App\MessageTemplate\RewardedMessage;
use App\MessageTemplate\Wechat\WechatRewardedMessage;
use App\Models\Order;
use App\Models\Question;
use App\Models\Thread;
use App\Models\UserWalletLog;
use App\Notifications\Rewarded;
use Illuminate\Support\Arr;

class SendNotificationOfWalletChanges
{
    /**
     * @param Saved $event
     */
    public function handle(Saved $event)
    {
        $user = $event->user;
        $data = $event->data;

        // 只是收款人接收收入通知，扣款人不接受扣款通知（没有扣款通知）
        if (isset($data['change_type'])) {
            switch ($data['change_type']) {
                case UserWalletLog::TYPE_INCOME_QUESTION_REWARD: // 35 问答答题收入通知
                    /**
                     * 查询问答提问支付订单信息
                     *
                     * @var Question $question
                     */
                    $question = Question::query()->where('id', $data['question_id'])->first();
                    $order = $question->thread->ordersByType(Order::ORDER_TYPE_QUESTION, false);

                    // 回答人接收收入通知
                    $user->notify(new Rewarded($order, $order->user, RewardedMessage::class));
                    $user->notify(new Rewarded($order, $user, WechatRewardedMessage::class, [
                        'message' => $order->thread->getContentByType(Thread::CONTENT_LENGTH, true),
                        'raw' => array_merge(Arr::only($order->toArray(), ['id', 'thread_id', 'type']), [
                            'actor_username' => $order->user->username,               // 发送人姓名
                            'actual_amount' => $order->calculateAuthorAmount(true),   // 获取实际金额
                        ]),
                    ]));
                    break;
            }
        }
    }
}
