<?php
declare(strict_types=1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: Cash.php 28830 2019-11-12 10:51 zhouzhou $
 */

namespace App\Events\Wallet;

use App\Models\UserWalletCash;
use App\Models\User;

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
     * @param User $actor
     */
    public function __construct(UserWalletCash $cash_record, $ip_address, $transfer_type)
    {
        $this->cash_record = $cash_record;
        $this->ip_address = $ip_address;
        $this->transfer_type = $transfer_type;
    }
}