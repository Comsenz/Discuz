<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Listeners\Order;

use App\Events\Order\Updated;
use App\Models\Group;
use App\Models\Thread;
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

            // 将用户移到普通会员
            $order->user->groups()->sync([Group::MEMBER_ID]);

            // 修改用户过期时间
            $order->user->expired_at = Carbon::now()->addDays($day);
            $order->user->save();
        }

        // 打赏主题的订单，支付成功后通知主题作者
        if ($order->type == Order::ORDER_TYPE_REWARD && $order->status == Order::ORDER_STATUS_PAID) {
            $order->payee->notify(new Rewarded($order));
        }

        //更新主题付费数
        if ($order->type == Order::ORDER_TYPE_THREAD && $order->status == Order::ORDER_STATUS_PAID) {
            $thread = Thread::where('id', $order->thread_id)->first();
            if ($thread) {
                $thread->refreshPaidCount();
                $thread->save();
            }
        }
    }
}
