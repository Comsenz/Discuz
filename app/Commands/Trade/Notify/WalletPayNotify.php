<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Commands\Trade\Notify;

use App\Events\Order\Updated;
use App\Models\Order;
use App\Models\UserWallet;
use App\Models\UserWalletLog;
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
            // 从钱包余额中扣除订单金额
            $userWallet = UserWallet::lockForUpdate()->find($this->data['user_id']);
            $userWallet->available_amount = $userWallet->available_amount - $this->data['amount'];
            $userWallet->save();

            // 记录钱包变更明细
            switch ($this->data['type']) {
                case Order::ORDER_TYPE_REGISTER:
                    $change_type = UserWalletLog::TYPE_EXPEND_RENEW;
                    $change_type_lang = 'wallet.expend_renew';
                    break;
                case Order::ORDER_TYPE_REWARD:
                    $change_type = UserWalletLog::TYPE_EXPEND_REWARD;
                    $change_type_lang = 'wallet.expend_reward';
                    break;
                case Order::ORDER_TYPE_THREAD:
                    $change_type = UserWalletLog::TYPE_EXPEND_THREAD;
                    $change_type_lang = 'wallet.expend_thread';
                    break;
                default:
                    $change_type      = $this->data['type'];
                    $change_type_lang = '';
            }

            UserWalletLog::createWalletLog(
                $this->data['user_id'],
                $this->data['amount'],
                0,
                $change_type,
                trans($change_type_lang),
                null,
                $this->data['id']
            );

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
                        'message' => trans('trade.wallet_pay_success'),
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
