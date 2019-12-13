<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 */

namespace App\Repositories;

use App\Models\Order;
use App\Models\User;
use Discuz\Foundation\AbstractRepository;
use Illuminate\Database\Eloquent\Builder;

class OrderRepository extends AbstractRepository
{
    /**
     * Get a new query builder for the categories table.
     *
     * @return Builder
     */
    public function query()
    {
        return Order::query();
    }

    /**
     * Find a order by order_sn, optionally making sure it is visible to a
     * certain user, or throw an exception.
     *
     * @param int $id
     * @param User|null $actor
     * @return Order
     */
    public function findOrderOrFail($order_sn, User $actor = null)
    {
        $query = $this->query()->where('order_sn', $order_sn);        
        return $this->scopeVisibleTo($query, $actor)->firstOrFail();
    }
}
