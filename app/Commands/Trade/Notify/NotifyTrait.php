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
use App\Models\PayNotify;
use App\Models\User;
use App\Models\UserWallet;
use App\Models\UserWalletLog;
use App\Settings\SettingsRepository;
use Illuminate\Contracts\Events\Dispatcher;

trait NotifyTrait
{
    /**
     * 当前订单
     *
     * @var Order $orderInfo
     */
    protected $orderInfo;

    /**
     * 支付成功，后续操作
     *
     * @param string $payment_sn 订单编号
     * @param string $trade_no 支付平台交易号
     * @param SettingsRepository $setting 配置
     * @param Dispatcher $events
     * @return Order|false|object|null
     */
    public function paymentSuccess($payment_sn, $trade_no, SettingsRepository $setting, Dispatcher $events)
    {
        // 查询订单
        $this->orderInfo = Order::query()->where('status', Order::ORDER_STATUS_PENDING)->where('payment_sn', $payment_sn)->first();

        if (!empty($this->orderInfo)) {
            // 修改通知数据
            PayNotify::query()->where('payment_sn', $payment_sn)->update(['status' => PayNotify::NOTIFY_STATUS_RECEIVED, 'trade_no' => $trade_no]);
            // 修改订单，已支付
            $this->orderInfo->status = Order::ORDER_STATUS_PAID;
            $this->orderInfo->save();

            switch ($this->orderInfo->type) {
                case Order::ORDER_TYPE_REGISTER:
                    // 注册时，返回支付成功。
                    return $this->orderInfo;
                case Order::ORDER_TYPE_REWARD:
                case Order::ORDER_TYPE_THREAD:
                case Order::ORDER_TYPE_BLOCK:
                    // 站长作者分成配置
                    $site_author_scale = $setting->get('site_author_scale');
                    $site_master_scale = $setting->get('site_master_scale');

                    if ($site_author_scale > 0 && $site_master_scale > 0 && ($site_author_scale + $site_master_scale) == 10) {
                        $this->orderInfo->calculateMasterAmount($bossAmount);
                    } else {
                        // 未设置或设置错误时站长分成为0，被打赏人检测是否有上级然后分成
                        $this->orderInfo->author_amount = $this->orderInfo->calculateAuthorAmount($bossAmount);
                    }

                    $this->orderInfo->save();

                    if ($this->orderInfo->author_amount > 0) {
                        //收款人钱包可用金额增加
                        $payee_id = $this->orderInfo->payee_id; //收款人
                        $user_wallet = UserWallet::query()->lockForUpdate()->find($payee_id);
                        $user_wallet->available_amount = $user_wallet->available_amount + $this->orderInfo->author_amount;
                        $user_wallet->save();

                        if ($bossAmount > 0) {
                            // 上级人钱包增加金额分成
                            if (!empty($userDistribution = $this->orderInfo->payee->userDistribution)) {
                                $parentUserId = $userDistribution->pid; // 上级user_id
                                $user_wallet = UserWallet::query()->lockForUpdate()->find($parentUserId);
                                $user_wallet->available_amount = $user_wallet->available_amount + $bossAmount;
                                $user_wallet->save();

                                // 添加分成钱包明细
                                $scaleOrderDetail = $this->orderByDetailType(true);

                                //添加钱包明细
                                UserWalletLog::createWalletLog(
                                    $parentUserId,              // 明细所属用户 id
                                    $bossAmount,                // 变动可用金额
                                    0,                          // 变动冻结金额
                                    $scaleOrderDetail['change_type'],
                                    trans($scaleOrderDetail['change_type_lang']),
                                    null,                       // 关联提现ID
                                    $this->orderInfo->id,       // 订单ID
                                    $this->orderInfo->payee_id  // 分成来源用户 = 订单收款人
                                );
                            }
                        }

                        // 收款人钱包明细记录
                        $payeeOrderDetail = $this->orderByDetailType();

                        // 添加钱包明细
                        UserWalletLog::createWalletLog(
                            $payee_id,
                            $this->orderInfo->author_amount,
                            0,
                            $payeeOrderDetail['change_type'],
                            trans($payeeOrderDetail['change_type_lang']),
                            null,
                            $this->orderInfo->id
                        );
                    }

                    return $this->orderInfo;

                case Order::ORDER_TYPE_GROUP:
                    $events->dispatch(
                        new PaidGroup($this->orderInfo->group_id, User::find($this->orderInfo->user_id), $this->orderInfo)
                    );
                    return $this->orderInfo;

                default:
                    break;
            }
        }

        return false;
    }

    /**
     * 获取对应订单明细类型
     *
     * @param false $scale
     * @return array
     */
    public function orderByDetailType($scale = false)
    {
        switch ($this->orderInfo->type) {
            case Order::ORDER_TYPE_REWARD:
                if ($scale) {
                    // 分成的类型
                    $change_type = UserWalletLog::TYPE_INCOME_SCALE_REWARD;
                    $change_type_lang = 'wallet.income_scale_reward';
                } else {
                    $change_type = UserWalletLog::TYPE_INCOME_REWARD;
                    $change_type_lang = 'wallet.income_reward';
                }
                break;
            case Order::ORDER_TYPE_THREAD:
                if ($scale) {
                    $change_type = UserWalletLog::TYPE_INCOME_SCALE_THREAD;
                    $change_type_lang = 'wallet.income_scale_thread';
                } else {
                    $change_type = UserWalletLog::TYPE_INCOME_THREAD;
                    $change_type_lang = 'wallet.income_thread';
                }
                break;
            case Order::ORDER_TYPE_BLOCK:
                // @TODO 编辑器 修改普通用户邀请注册分成
                $change_type = UserWalletLog::TYPE_INCOME_BLOCK;
                $change_type_lang = 'wallet.income_block';
                break;
            default:
                $change_type = $this->orderInfo->type;
                $change_type_lang = '';
        }

        return ['change_type' => $change_type, 'change_type_lang' => $change_type_lang];
    }
}
