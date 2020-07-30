<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\BlockEditor\Formater;

use App\Models\Order;
use Discuz\Auth\Guest;

class PaidCheck
{
    public static function isPaid($post_id, array $block_payid)
    {
        $actor = app('request')->getAttribute('actor');

        if ($actor instanceof Guest) {
            return false;
        } else {
            return $actor->orders()
                ->where('status', Order::ORDER_STATUS_PAID)
                ->where('post_id', $post_id)
                ->whereIn('block_payid', $block_payid)
                ->exists();
        }
    }
}