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
use App\Models\User;
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
     * @param  Updated $event [description]
     * @return [type]         [description]
     */
    public function whenReward(Updated $event)
    {
        if ($event->order->type == Order::ORDER_TYPE_REWARD && $event->order->status == Order::ORDER_STATUS_PAID) {
       		$order_info = Order::with('user')->with('thread')->find($event->order->id);
        	$payee = User::find($event->order->payee_id);
        	if (!empty($payee) && !empty($order_info)) {	
        		$info = [
        			'username' => $order_info->getRelation('user')->username,
        			'user_id' => $order_info->user_id,
	        		'info' => '打赏了我' . $order_info->amount . '元',
	        		'post_content' => $order_info->thread->firstPost->content,
	        		'thread_id' => $order_info->getRelation('thread')->id,
	        		'extra' => [
						'reward_amount' => $order_info->amount
	        		]
        		];
        		$payee->notify(new Rewarded($info));
        	}
        }

    }
}
