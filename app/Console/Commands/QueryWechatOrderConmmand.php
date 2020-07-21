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

namespace App\Console\Commands;

use App\Events\Order\Updated;
use App\Settings\SettingsRepository;
use App\Trade\Config\GatewayConfig;
use App\Trade\QueryTrade;
use Carbon\Carbon;
use Discuz\Console\AbstractCommand;
use Discuz\Foundation\Application;
use App\Models\Order;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\ConnectionInterface;
use App\Commands\Trade\Notify\NotifyTrait;

class QueryWechatOrderConmmand extends AbstractCommand
{
    use NotifyTrait;

    protected $app;

    protected $setting;

    protected $connection;

    protected $events;

    public function __construct(Application $app, SettingsRepository $setting, ConnectionInterface $connection, Dispatcher $events)
    {
        $this->app = $app;
        $this->setting = $setting;
        $this->connection = $connection;
        $this->events = $events;
        parent::__construct();
    }

    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'order:query';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check whether the order has been paid';

    /**
     * @inheritDoc
     */
    protected function handle()
    {
        //查询已经过期的订单
        $query_orders = Order::where('status', Order::ORDER_STATUS_PENDING)
            ->where('created_at', '<', Carbon::now()->subMinute(Order::ORDER_EXPIRE_TIME + 1))
            ->limit(500)
            ->get();
        if ($query_orders->count()) {
            $wechat_config = $this->setting->tag('wxpay');
            $result_data = false;//支付结果
            $payment_sn = '';//商户交易号
            $trade_no = '';//微信交易号
            foreach ($query_orders as $order) {
                switch ($order->payment_type) {
                    case Order::PAYMENT_TYPE_WECHAT_NATIVE: //微信扫码支付
                    case Order::PAYMENT_TYPE_WECHAT_WAP: //微信h5支付
                    case Order::PAYMENT_TYPE_WECHAT_JS: //微信网页、公众号
                    case Order::PAYMENT_TYPE_WECHAT_MINI: //微信小程序支付
                        $result_data = $this->queryWecahtOrderStatus($wechat_config, $order->payment_sn);
                        if ($result_data) {
                            $payment_sn = $result_data['out_trade_no'];//商户交易号
                            $trade_no = $result_data['transaction_id'];//微信交易号
                            $log = app('payLog');
                            $log->info('console: ', [$result_data]);
                        }
                        break;
                    default:
                        break;
                }
                if ($result_data) {
                    //支付成功
                    //开始事务
                    $this->connection->beginTransaction();
                    try {
                        //支付成功处理
                        $order_info = $this->paymentSuccess($payment_sn, $trade_no, $this->setting, $this->events);
                        if ($order_info) {
                            $this->events->dispatch(
                                new Updated($order_info)
                            );
                        }
                        $this->connection->commit();
                    } catch (Exception $e) {
                        //回滚事务
                        $this->connection->rollback();
                        $log = app('payLog');
                        $log->info('console: ', $e->getMessage());
                    }
                } else {
                    //订单未支付
                    //设置订单已过期
                    $order->status = Order::ORDER_STATUS_EXPIRED;
                    $order->save();
                }
            }
        }
    }

    /**
     * 查询微信支付订单
     * $param  mixed $config 微信配置
     * @param  string $payment_sn  商户交易号
     */
    public function queryWecahtOrderStatus($config, $payment_sn)
    {
        $query_result = QueryTrade::query(GatewayConfig::WECAHT_PAY_QUERY, $config, null, $payment_sn);
        if (isset($query_result['return_code'])
            && isset($query_result['result_code'])
            && isset($query_result['trade_state'])
            && $query_result['return_code'] == 'SUCCESS'
            && $query_result['result_code'] == 'SUCCESS'
            && $query_result['trade_state'] == 'SUCCESS') {
            return $query_result;
        }
        return false;
    }
}
