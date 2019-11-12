<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: CashUserWalletListner.php xxx 2019-11-12 11:14:00 zhouzhou $
 */

namespace App\Listeners\Wallet;

use App\Events\Wallet\Cash;
use App\Trade\Config\GatewayConfig;
use App\Trade\TransferTrade;

class CashUserWalletListner
{


    public function handle(Cash $event)
    {
dd('dd');
    	file_put_contents('d:/web/test.txt','sssssssss');
        //print_r($event);
        //exit;
    }
}
