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

class TransferTrade
{
    /**
     * 打款结果
     * @var boolean
     */
    private static $transfer_status = false;

    /**
     * 打款结果信息
     * @var mixed
     */
    private static $transfer_response;

    /**
     * 企业付款
     * @param  string $transfer_type 付款类型
     * @param  mixed $config      配置信息
     * @param  array  $extra      其他参数
     * @return mixed              付款信息
     */
    public static function transfer($transfer_type, $config, $extra = [])
    {
        $result = []; //返回参数
        switch ($transfer_type) {
            case GatewayConfig::WECAHT_TRANSFER: //微信企业付款
                $result = self::wechatTransfer($config, $extra);
                break;
            default:
                break;
        }
        return $result;
    }

    /**
     * 微信企业付款
     * @param  array $config        支付配置
     * @param  array  $extra        其他参数
     * @return mixed                交易结果
     */
    public static function wechatTransfer($config, $extra = [])
    {
        $gateway = Omnipay::create(GatewayConfig::WECAHT_TRANSFER);
        $gateway->setAppId($config['app_id']);
        $gateway->setMchId($config['mch_id']);
        $gateway->setApiKey($config['api_key']);
        $gateway->setCertPath($config['cert_path']);
        $gateway->setKeyPath($config['key_path']);
        $response = $gateway->transfer($extra)->send();
        if ($response->isSuccessful()) {
            self::$transfer_status = true;
        } else {
            self::$transfer_status = false;
        }
        self::$transfer_response = $response->getData();
    }

    /**
     * 获取付款结果
     * @return boolean 付款结果
     */
    public static function getTransferStatus()
    {
        return self::$transfer_status;
    }

    /**
     * 获取付款结果数组
     * @return [type] [description]
     */
    public static function getTransferRespone()
    {
        return self::$transfer_response;
    }
}
