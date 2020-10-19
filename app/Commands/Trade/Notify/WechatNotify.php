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

namespace App\Commands\Trade\Notify;

use App\Trade\Config\GatewayConfig;
use App\Trade\NotifyTrade;
use App\Trade\QueryTrade;
use App\Settings\SettingsRepository;
use Exception;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Contracts\Events\Dispatcher;
use Discuz\Foundation\EventsDispatchTrait;
use App\Events\Order\Updated;

class WechatNotify
{
    use NotifyTrait;
    use EventsDispatchTrait;

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
    }

    /**
     * 执行命令
     * @param SettingsRepository $setting
     * @param ConnectionInterface $connection
     * @param Dispatcher $events
     * @return mixed 返回给支付平台数据
     */
    public function handle(SettingsRepository $setting, ConnectionInterface $connection, Dispatcher $events)
    {
        $this->config = $setting->tag('wxpay');
        $notify_result = NotifyTrade::notify(GatewayConfig::WECAHT_PAY_NOTIFY, $this->config);
        if (isset($notify_result['result_code']) && $notify_result['result_code'] == 'SUCCESS') {
            //支付成功
            if ($this->queryOrderStatus($notify_result['transaction_id'])) {
                $log = app('payLog');
                try {
                    $log->info('notify', $notify_result);
                } catch (Exception $e) {
                    goto todo;
                }

                todo:
                $payment_sn = $notify_result['out_trade_no'];//商户交易号
                $trade_no = $notify_result['transaction_id'];//微信交易号
                //开始事务
                $connection->beginTransaction();
                try {
                    //支付成功处理
                    $order_info = $this->paymentSuccess($payment_sn, $trade_no, $setting, $events);
                    if ($order_info) {
                        $events->dispatch(
                            new Updated($order_info)
                        );
                    }
                    $connection->commit();
                    if ($order_info) {
                        return '<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>';
                    }
                } catch (Exception $e) {
                    //回滚事务
                    $connection->rollback();
                    throw new ErrorException($e->getMessage(), 500);
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
        $query_result = QueryTrade::query(GatewayConfig::WECAHT_PAY_QUERY, $this->config, $transaction_id);
        if (isset($query_result['return_code'])
            && isset($query_result['result_code'])
            && isset($query_result['trade_state'])
            && $query_result['return_code'] == 'SUCCESS'
            && $query_result['result_code'] == 'SUCCESS'
            && $query_result['trade_state'] == 'SUCCESS'
        ) {
            return true;
        }
        return false;
    }
}
