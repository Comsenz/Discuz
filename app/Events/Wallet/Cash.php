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

class Cash
{
    /**
     * @var UserWalletCash
     */
    public $cash_record;

    /**
     * @param UserWalletCash $cash_record
     */
    public function __construct(UserWalletCash $cash_record)
    {

        $this->cash_record = $cash_record;
    }
}