<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
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
     */
    public function __construct(UserWalletCash $cash_record, $ip_address, $transfer_type)
    {
        $this->cash_record = $cash_record;
        $this->ip_address = $ip_address;
        $this->transfer_type = $transfer_type;
    }
}
