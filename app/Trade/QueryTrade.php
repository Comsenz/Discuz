<?php


/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Trade;

use App\Trade\Config\GatewayConfig;
use Omnipay\Omnipay;

class QeuryTrade
{
    /**
     * 查询交易
     * @param  string $query_type 查询类型
     * @param  string $trade_no   交易号
     * @param  mixed $config      配置信息
     * @param  array  $extra      其他参数
     * @return mixed              支付信息
     */
    public static function query($query_type, $config, $trade_no, $extra = [])
    {
        $result = []; //返回参数
        switch ($query_type) {
            case GatewayConfig::WECAHT_PAY_QUERY: //微信付款查询
                $result = self::wechatQuery($trade_no, $config, $extra);
                break;
            default:
                break;
        }
        return $result;
    }

    public static function wechatQuery($config, $trade_no, $extra = [])
    {
        $gateway = Omnipay::create(GatewayConfig::WECAHT_PAY_QUERY);
        $gateway->setAppId($config['app_id']);
        $gateway->setMchId($config['mch_id']);
        $gateway->setApiKey($config['api_key']);
        $response = $gateway->query([
            'transaction_id' => $trade_no, //交易号
        ])->send();

        if ($response->isSuccessful()) {
            return $response->getData();
        } else {
        }
    }
}
