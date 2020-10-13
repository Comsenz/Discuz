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
        $log = app('payLog');
        try {
            $log->info('notify', $this->data);
        } catch (Exception $e) {
            goto todo;
        }

        todo:
        $payment_sn = $this->data['payment_sn'];//商户交易号
        $trade_no = $this->data['payment_sn'];//微信交易号

        //开始事务
        $connection->beginTransaction();
        try {
            // 从钱包余额中扣除订单金额
            $userWallet = UserWallet::query()->lockForUpdate()->find($this->data['user_id']);
            $userWallet->available_amount = $userWallet->available_amount - $this->data['amount'];

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
                case Order::ORDER_TYPE_GROUP:
                    $change_type = UserWalletLog::TYPE_EXPEND_GROUP;
                    $change_type_lang = 'wallet.expend_group';
                    break;
                case Order::ORDER_TYPE_QUESTION:
                    $change_type = UserWalletLog::TYPE_QUESTION_FREEZE;
                    $change_type_lang = 'wallet.question_freeze_desc';
                    // 钱包&钱包日志 增加冻结金额数
                    $userWallet->freeze_amount = $userWallet->freeze_amount + $this->data['amount'];
                    $freezeAmount = $this->data['amount'];
                    break;
                case Order::ORDER_TYPE_ONLOOKER:
                    $change_type = UserWalletLog::TYPE_EXPEND_ONLOOKER;
                    $change_type_lang = 'wallet.expend_onlooker';
                    break;
                case Order::ORDER_TYPE_ATTACHMENT:
                    $change_type = UserWalletLog::TYPE_EXPEND_ATTACHMENT;
                    $change_type_lang = 'wallet.expend_attachment';
                    break;
                default:
                    $change_type = $this->data['type'];
                    $change_type_lang = '';
            }

            $userWallet->save();

            UserWalletLog::createWalletLog(
                $this->data['user_id'],
                -$this->data['amount'],
                $freezeAmount ?? 0,
                $change_type,
                trans($change_type_lang),
                null,
                $this->data['id']
            );

            // 支付成功处理
            $order_info = $this->paymentSuccess($payment_sn, $trade_no, $setting, $events);

            if ($order_info) {
                $events->dispatch(
                    new Updated($order_info)
                );
            }
            $connection->commit();
            if ($order_info) {
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
