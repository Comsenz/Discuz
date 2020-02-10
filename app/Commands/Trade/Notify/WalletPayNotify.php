<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Commands\Trade\Notify;

use App\Events\Order\Updated;
use App\Settings\SettingsRepository;
use ErrorException;
use Exception;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\ConnectionInterface;

class WalletPayNotify
{
    use NotifyTrait;

    /**
     * @var array
     */
    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * @param ConnectionInterface $connection
     * @param Dispatcher $events
     * @param SettingsRepository $setting
     * @return array
     * @throws ErrorException
     */
    public function handle(ConnectionInterface $connection, Dispatcher $events, SettingsRepository $setting)
    {
        $log = app('log');
        $log->info('notify', $this->data);
        $payment_sn = $this->data['payment_sn'];//商户交易号
        $trade_no = $this->data['payment_sn'];//微信交易号
        //开始事务
        $connection->beginTransaction();
        try {
            //支付成功处理
            $order_info = $this->paymentSuccess($payment_sn, $trade_no, $setting);
            $connection->commit();
            if ($order_info) {
                $events->dispatch(
                    new Updated($order_info)
                );
                return [
                    'wallet_pay' => [
                        'result' => 'success',
                        'message' => '支付成功',
                    ]
                ];
            }
        } catch (Exception $e) {
            //回滚事务
            $connection->rollback();
            throw new ErrorException($e->getMessage(), 500);
        }
    }
}
