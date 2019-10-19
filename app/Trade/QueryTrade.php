<?php
declare(strict_types=1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: QeuryTrade.php xxx 2019-10-16 11:10 zhouzhou $
 */

namespace App\Trade;

use App\Trade\Config\GatewayConfig;

class QeuryTrade
{
	public static function test()
	{
		echo GatewayConfig::WECAHT_PAY_WAP;
		echo 'dssssssd';
	}
}