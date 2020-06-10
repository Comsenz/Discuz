<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Events\Group;

use App\Models\Order;
use App\Models\User;

class PaidGroup
{

    /**
     * @var integer
     */
    public $group_id;
    /**
     * @var Order
     */
    public $order;

    /**
     * @var User
     */
    public $user;

    /**
     * @var User
     */
    public $operator;

    /**
     * @param Order $order
     * @param User $actor
     */
    public function __construct($group_id, User $user, Order $order = null, User $operator = null)
    {
        $this->group_id = $group_id;
        $this->user = $user;
        $this->order = $order;
        $this->operator = $operator;
    }
}
