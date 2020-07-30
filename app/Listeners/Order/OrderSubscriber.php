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
use App\MessageTemplate\Wechat\WechatRewardedMessage;
use App\Models\Thread;
use Carbon\Carbon;
use Discuz\Contracts\Setting\SettingsRepository;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Events\Dispatcher;
use App\Models\Order;
use App\Notifications\Rewarded;
use Illuminate\Support\Arr;

class OrderSubscriber
{
    public function subscribe(Dispatcher $events)
    {
        // 订单支付成功
        $events->listen(Updated::class, [$this, 'whenOrderPaid']);
    }

    /**
     * 支付完成时
     *
     * @param Updated $event
     * @throws BindingResolutionException
     */
    public function whenOrderPaid(Updated $event)
    {
        $order = $event->order;

        // 付费加入站点的订单，支付成功后修改用户信息
        if ($order->type == Order::ORDER_TYPE_REGISTER && $order->status == Order::ORDER_STATUS_PAID) {
            $day = app()->make(SettingsRepository::class)->get('site_expire');

            // 修改用户过期时间、订单过期时间,如果没有有效期，订单过期时间设置为null
            $order->user->expired_at = Carbon::now()->addDays($day);
            $order->expired_at = $day ? Carbon::now()->addDays($day) : null;
            $order->user->save();
            $order->save();
        }

        // 打赏主题的订单，支付成功后通知主题作者
        if ($order->type == Order::ORDER_TYPE_REWARD && $order->status == Order::ORDER_STATUS_PAID) {
            // 发送通知
            $order->payee->notify(new Rewarded($order, $order->user, RewardedMessage::class));
            $order->payee->notify(new Rewarded($order, $order->user, WechatRewardedMessage::class, [
                'message' => $order->thread->getContentByType(Thread::CONTENT_LENGTH, true),
                'raw' => array_merge(Arr::only($order->toArray(), ['id', 'thread_id', 'type']), [
                    'actor_username' => $order->user->username,    // 发送人姓名
                    'actual_amount' => $order->actual_amount,      // 获取实际金额
                ]),
            ]));

            // 更新主题打赏数
            $order->thread->refreshRewardedCount()->save();
        }

        // 更新主题付费数
        if ($order->type == Order::ORDER_TYPE_THREAD && $order->status == Order::ORDER_STATUS_PAID) {
            // 发送通知
            $order->payee->notify(new Rewarded($order, $order->user, RewardedMessage::class));
            $order->payee->notify(new Rewarded($order, $order->user, WechatRewardedMessage::class, [
                'message' => $order->thread->getContentByType(Thread::CONTENT_LENGTH, true),
                'raw' => array_merge(Arr::only($order->toArray(), ['id', 'thread_id', 'type']), [
                    'actor_username' => $order->user->username,    // 发送人姓名
                    'actual_amount' => $order->actual_amount       // 获取实际金额
                ]),
            ]));

            $order->thread->refreshPaidCount()->save();
        }
    }
}
