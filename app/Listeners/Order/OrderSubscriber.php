<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Listeners\Order;

use App\Events\Order\Updated;
use Carbon\Carbon;
use Discuz\Contracts\Setting\SettingsRepository;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Events\Dispatcher;
use App\Models\Order;
use App\Notifications\Rewarded;

class OrderSubscriber
{
    public function subscribe(Dispatcher $events)
    {
        // 打赏通知
        $events->listen(Updated::class, [$this, 'whenReward']);
    }

    /**
     * 支付完成时
     *
     * @param Updated $event
     * @throws BindingResolutionException
     */
    public function whenReward(Updated $event)
    {
        $order = $event->order;

        // 付费加入的订单，修改用户过期时间
        if ($order->type == Order::ORDER_TYPE_REGISTER) {
            $day = app()->make(SettingsRepository::class)->get('site_expire');

            $order->user->expired_at = Carbon::now()->addDays($day);
        }

        if ($order->type == Order::ORDER_TYPE_REWARD && $order->status == Order::ORDER_STATUS_PAID) {
            $order->payee->notify(new Rewarded($order));
        }
    }
}
