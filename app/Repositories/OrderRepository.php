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
