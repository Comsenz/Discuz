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

namespace App\Trade\Config;

class GatewayConfig
{
    const WALLET_PAY = 'walletPay'; //用户钱包支付

    const WECAHT_PAY_WAP = 'WechatPay_Mweb'; //微信H5支付网关

    const WECAHT_PAY_JS = 'WechatPay_Js'; //微信网页、公众号、小程序支付网关

    const WECAHT_PAY_NATIVE = 'WechatPay_Native'; //微信扫码支付

    const WECAHT_PAY_NOTIFY = 'WechatPay'; //微信支付异步通知

    const WECAHT_PAY_QUERY = 'WechatPay'; //微信支付支付查询

    const WECAHT_TRANSFER = 'WechatPay'; //微信支付企业付款
}
