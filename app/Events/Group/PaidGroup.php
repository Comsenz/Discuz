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
     * @param $group_id
     * @param User $user
     * @param Order $order
     * @param User|null $operator
     */
    public function __construct($group_id, User $user, Order $order = null, User $operator = null)
    {
        $this->group_id = $group_id;
        $this->user = $user;
        $this->order = $order;
        $this->operator = $operator;
    }
}
