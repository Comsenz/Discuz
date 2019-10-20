<?php
declare(strict_types=1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: GatewayConfig.php xxx 2019-10-16 00::00 zhouzhou $
 */

namespace App\Trade\Config;

class GatewayConfig
{
	const WECAHT_PAY_WAP = 'WechatPay_Mweb'; //微信H5支付网关

	const WECAHT_PAY_JS = 'WechatPay_Js'; //微信网页、公众号、小程序支付网关

	const WECAHT_PAY_NATIVE = 'WechatPay_Native'; //微信扫码支付

	const WECAHT_PAY_NOTIFY = 'WechatPay'; //微信支付异步通知

	const WECAHT_PAY_QUERY = 'WechatPay'; //微信支付支付查询
}
