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

class NotifyTrade
{
    /**
     * 通知
     * @param  string $notify_type 通知类型
     * @param  array $config      配置信息
     * @param  mixed $extra       其他参数
     * @return mixed            支付验证结果
     */
    public static function notify($notify_type, $config, $extra = [])
    {
        $result = [];
        switch ($notify_type) {
            case GatewayConfig::WECAHT_PAY_NOTIFY: //微信支付通知
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
        $gateway = Omnipay::create($notify_type);
        $gateway->setAppId($config['app_id']);
        $gateway->setMchId($config['mch_id']);
        $gateway->setApiKey($config['api_key']);

        $response = $gateway->completePurchase([
            'request_params' => file_get_contents('php://input'),
        ])->send();

        if ($response->isPaid()) {
            //pay success
            return $response->getRequestData();
        } else {
            //pay fail
            return;
        }
    }
}
