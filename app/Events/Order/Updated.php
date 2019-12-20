<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Events\Order;

use App\Models\Order;
use App\Models\User;

class Updated
{
    /**
     * @var Order
     */
    public $order;

    /**
     * @var User
     */
    public $actor;

    /**
     * Updated constructor.
     *
     * @param Order $order
     * @param User $actor
     */
    public function __construct(Order $order, User $actor = null)
    {
        $this->order = $order;
        $this->actor = $actor;
    }
}
