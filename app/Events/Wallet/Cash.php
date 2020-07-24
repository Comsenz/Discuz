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

use App\Models\UserWalletCash;

class Cash
{
    /**
     * @var UserWalletCash
     */
    public $cash_record;

    /**
     * ip地址
     * @var string
     */
    public $ip_address;

    /**
     * 付款渠道
     * @var string
     */
    public $transfer_type;

    /**
     * @param UserWalletCash $cash_record
     * @param $ip_address
     * @param $transfer_type
     */
    public function __construct(UserWalletCash $cash_record, $ip_address, $transfer_type)
    {
        $this->cash_record = $cash_record;
        $this->ip_address = $ip_address;
        $this->transfer_type = $transfer_type;
    }
}
