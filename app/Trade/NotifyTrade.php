<?php
declare(strict_types=1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: NotifyTrade.php xxx 2019-10-16 11:10 zhouzhou
 */

namespace App\Trade;

use App\Trade\Config\GatewayConfig;
use Omnipay\Omnipay;
use Psr\Log\LoggerInterface;

class NotifyTrade
{
	/**
	 * 通知
	 * @param  string $notify_type 通知类型
	 * @param  mixed $extra       其他参数
	 * @return mixed            支付验证结果
	 */
	public static function notify($notify_type, $config, $extra = array())
	{	
		$result = [];
		switch ($notify_type) {
			case GatewayConfig::WECAHT_PAY_NOTIFY://微信支付通知
				$result = self::wechatNotify($notify_type, $config);
				break;
			default:
				break;
		}
		return $result;
	}

	/**
	 * 微信支付通知
	 * @param  string $notify_type 通知类型
	 * @param  array $config      配置信息
	 * @return string
	 */
	public static function wechatNotify($notify_type, $config)
	{
		$gateway    = Omnipay::create($notify_type);
		$gateway->setAppId($config['app_id']);
		$gateway->setMchId($config['mch_id']);
		$gateway->setApiKey($config['api_key']);

		$response = $gateway->completePurchase([
		    'request_params' => file_get_contents('php://input')
		])->send();

		if ($response->isPaid()) {
			//pay success
			return $response->getRequestData();
		}else{
		    //pay fail
		    return;
		}
	}
}