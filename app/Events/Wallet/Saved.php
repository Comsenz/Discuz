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

namespace App\Events\Wallet;

use App\Models\User;
use App\Models\UserWallet;

class Saved
{
    /**
     * @var UserWallet
     */
    public $wallet;

    /**
     * @var User
     */
    public $user;

    /**
     * @var float
     */
    public $amount;

    /**
     * @var array
     */
    public $data;

    /**
     * @param UserWallet $wallet
     * @param User $user
     * @param float $amount
     * @param array $data
     */
    public function __construct(UserWallet $wallet, User $user, $amount, $data = [])
    {
        $this->wallet = $wallet;
        $this->user = $user;
        $this->amount = $amount;
        $this->data = $data;
    }
}
