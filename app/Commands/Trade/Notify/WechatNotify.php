<?php
declare (strict_types = 1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: WechatNotify.php XXX 2019-10-18 14:00 zhouzhou $
 */

namespace App\Commands\Trade\Notify;

use App\Models\Order;
use App\Models\PayNotify;
use App\Trade\Config\GatewayConfig;
use App\Trade\NotifyTrade;
use App\Trade\QeuryTrade;

class WechatNotify
{
    /**
     * 微信配置参数
     * @var array
     */
    public $config;

    /**
     * 初始化命令参数
     */
    public function __construct()
    {
        $this->config = [
            'app_id'  => 'wx24ef8273fde3334e',
            'mch_id'  => '1515287121',
            'api_key' => 'sajhulDAAhkH1341H9H1O1OR31O12e1o',
        ];
    }

    /**
     * 执行命令
     * @return mixed 返回给支付平台数据
     */
    public function handle()
    {
        $notify_result = NotifyTrade::notify(GatewayConfig::WECAHT_PAY_NOTIFY, $this->config);
        if (isset($notify_result['result_code']) && $notify_result['result_code'] == 'SUCCESS') {
//支付成功
            if ($this->queryOrderStatus($notify_result['transaction_id'])) {
                $log = app('log');
                $log->info('notify', $notify_result);
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
        }
        return '';
    }

    /**
     * 查询微信支付订单
     * @param  string $transaction_id 微信交易号
     * @return boolean                是否支付成功
     */
    public function queryOrderStatus($transaction_id)
    {
        $query_result = QeuryTrade::query(GatewayConfig::WECAHT_PAY_QUERY, $transaction_id, $this->config);
        if (isset($query_result['return_code'])
            && isset($query_result['result_code'])
            && $query_result['return_code'] == "SUCCESS"
            && $query_result['result_code'] == "SUCCESS") {

            return true;
        }
        return false;
    }
}
