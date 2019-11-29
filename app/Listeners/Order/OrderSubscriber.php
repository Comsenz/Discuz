<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: OrderListener.php xxx 2019-11-21 16:48:00 zhouzhou $
 */

namespace App\Listeners\Order;

use App\Events\Order\Updated;
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
     */
    public function whenReward(Updated $event)
    {
        $order = $event->order;

        if ($order->type == Order::ORDER_TYPE_REWARD && $order->status == Order::ORDER_STATUS_PAID) {
            $order->payee->notify(new Rewarded($order));
        }
    }
}
