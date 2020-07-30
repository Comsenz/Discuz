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

use App\Events\Group\PaidGroup;
use App\Models\Order;
use App\Models\User;
use App\Models\PayNotify;
use App\Models\UserWallet;
use App\Models\UserWalletLog;
use App\Settings\SettingsRepository;
use Illuminate\Contracts\Events\Dispatcher;

trait NotifyTrait
{
    /**
     * 支付成功，后续操作
     * @param string $payment_sn 订单编号
     * @param string $trade_no 支付平台交易号
     * @param SettingsRepository $setting 配置
     * @param Dispatcher $events
     * @return Order
     */
    public function paymentSuccess($payment_sn, $trade_no, SettingsRepository $setting, Dispatcher $events)
    {
        //查询订单
        $order_info = Order::where('status', Order::ORDER_STATUS_PENDING)->where('payment_sn', $payment_sn)->first();
        if (!empty($order_info)) {
            //修改通知数据
            $pay_notify_result = PayNotify::where('payment_sn', $payment_sn)
                ->update(['status' => PayNotify::NOTIFY_STATUS_RECEIVED, 'trade_no' => $trade_no]);
            //修改订单,已支付
            $order_info->status = Order::ORDER_STATUS_PAID;
            $order_info->save();

            switch ($order_info->type) {
                case Order::ORDER_TYPE_REGISTER:
                    //注册时，返回支付成功。
                    return $order_info;
                    //break;
                case Order::ORDER_TYPE_REWARD:
                case Order::ORDER_TYPE_THREAD:
                    $order_amount = $order_info->amount;//订单金额
                    //站长作者分成配置
                    $site_author_scale = $setting->get('site_author_scale');
                    $site_master_scale = $setting->get('site_master_scale');

                    $payee_amount = 0.00;//收款人分成金额
                    $master_amount = 0.00;//站长分成金额
                    if ($site_author_scale > 0
                        && $site_master_scale > 0
                        && ($site_author_scale + $site_master_scale) == 10) {
                        $author_ratio = $site_author_scale / 10;
                        $payee_amount = sprintf('%.2f', ($order_amount * $author_ratio));
                        $master_amount = $order_amount - $payee_amount;
                    } else {
                        //未设置或设置错误时站长分成为0,被打赏人分得全部
                        $payee_amount = $order_amount;
                    }
                    //设置分成
                    $order_info->master_amount = $master_amount;
                    $order_info->save();

                    if ($payee_amount > 0) {
                        //收款人钱包可用金额增加
                        $payee_id                      = $order_info->payee_id; //收款人
                        $user_wallet                   = UserWallet::lockForUpdate()->find($payee_id);
                        $user_wallet->available_amount = $user_wallet->available_amount + $payee_amount;
                        $user_wallet->save();

                        //收款人钱包明细记录
                        switch ($order_info->type) {
                            case Order::ORDER_TYPE_REWARD:
                                $change_type = UserWalletLog::TYPE_INCOME_REWARD;
                                $change_type_lang = 'wallet.income_reward';
                                break;
                            case Order::ORDER_TYPE_THREAD:
                                $change_type = UserWalletLog::TYPE_INCOME_THREAD;
                                $change_type_lang = 'wallet.income_thread';
                                break;
                            default:
                                $change_type = $order_info->type;
                                $change_type_lang = '';
                        }

                        //添加钱包明细
                        $user_wallet_log = UserWalletLog::createWalletLog(
                            $payee_id,
                            $payee_amount,
                            0,
                            $change_type,
                            trans($change_type_lang),
                            null,
                            $order_info->id
                        );
                    }
                    return $order_info;
                    //break;
                case Order::ORDER_TYPE_GROUP:
                    $events->dispatch(
                        new PaidGroup($order_info->group_id, User::find($order_info->user_id), $order_info)
                    );
                    return $order_info;
                    //break;
                default:
                    //
                    break;
            }
        }
        return false;
    }
}
