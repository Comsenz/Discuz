<?php
declare(strict_types=1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: WechatNotify.php XXX 2019-10-18 14:00 zhouzhou $
 */

namespace App\Commands\Trade\Notify;

use Illuminate\Support\Collection;
use App\Trade\Config\GatewayConfig;
use App\Trade\NotifyTrade;
use App\Models\Order;
use App\Models\PayNotify;

class WechatNotify
{
    /**
     * 初始化命令参数
     */
    public function __construct()
    {
    }

    /**
     * 执行命令
     * @return mixed 返回给支付平台数据
     */
    public function handle()
    {
    	$config = [
    		'app_id' => '',
    		'mch_id' => '',
    		'api_key' => '',
            'notify_url' => '',
    	];
		$notify_result = NotifyTrade::notify(GatewayConfig::WECAHT_PAY_NOTIFY, $config);
		if (isset($notify_result['result_code']) && $notify_result['result_code'] == 'SUCCESS') {//支付成功		
			//$log = app('log');
			//$log->info('notify', $notify_result);
			$payment_sn = $notify_result['out_trade_no'];
			//修改通知数据
			$pay_notify_result = PayNotify::where('payment_sn', $payment_sn)
				->update(['status' => 1, 'trade_no' => $notify_result['transaction_id']]);
			//修改订单,已支付
			$order_result = Order::where('payment_sn', $payment_sn)->update(['status' => 1]);

			if ($order_result) {
				return '<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>';
			}
		}
		return '';
    }
}