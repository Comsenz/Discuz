<?php

/**
 * Copyright (C) 2020 Tencent Cloud.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace App\Trade;

use App\Trade\Config\GatewayConfig;
use Omnipay\Omnipay;

class QueryTrade
{
    /**
     * 查询交易
     * @param  string $query_type 查询类型
     * @param  mixed $config      配置信息
     * @param  string $trade_no   支付平台交易号
     * @param  string $payment_sn 商户交易号
     * @param  array  $extra      其他参数
     * @return mixed              支付信息
     */
    public static function query($query_type, $config, $trade_no, $payment_sn = null, $extra = [])
    {
        $result = []; //返回参数
        switch ($query_type) {
            case GatewayConfig::WECAHT_PAY_QUERY: //微信付款查询
                $result = self::wechatQuery($config, $trade_no, $payment_sn, $extra);
                break;
            default:
                break;
        }
        return $result;
    }

    public static function wechatQuery($config, $trade_no, $payment_sn = null, $extra = [])
    {
        $gateway = Omnipay::create(GatewayConfig::WECAHT_PAY_QUERY);
        $gateway->setAppId($config['app_id']);
        $gateway->setMchId($config['mch_id']);
        $gateway->setApiKey($config['api_key']);
        if (!empty($trade_no)) {
            $query_data = [
                'transaction_id' => $trade_no, //支付平台交易号
            ];
        } elseif (!empty($payment_sn)) {
            $query_data = [
                'out_trade_no' => $payment_sn, //商户交易号
            ];
        }
        $response = $gateway->query($query_data)->send();

        if ($response->isSuccessful()) {
            return $response->getData();
        } else {
        }
    }
}
