<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: CashReviewSubscriber.php xxx 2019-11-12 16:14:00 zhouzhou $
 */

namespace App\Listeners\Wallet;

use Illuminate\Contracts\Events\Dispatcher;
use App\Events\Wallet\Cash;

class CashReviewSubscriber
{
    public function subscribe(Dispatcher $events)
    {
        //审核成功提现
		$events->listen(Cash::class, CashTransfer::class);
    }
}
