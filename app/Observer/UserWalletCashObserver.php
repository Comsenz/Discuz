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

namespace App\Observer;

use App\Models\UserWalletCash;
use App\Notifications\Withdrawal;

class UserWalletCashObserver
{
    public function updated(UserWalletCash $cash)
    {
        // 修改状态后 - 发送通知
        $this->sendNotification($cash);
    }

    public function sendNotification($cash)
    {
        /**
         * 判断如果是 企业零钱付款，必须是回调打款后，已打款的状态再发送通知
         */
        if ($cash->cash_type == UserWalletCash::TRANSFER_TYPE_MCH) {
            // 判断是否已打款
            if ($cash->cash_status != UserWalletCash::STATUS_PAID) {
                // 如果不是审核拒绝就不发送通知
                if ($cash->cash_status != UserWalletCash::STATUS_REVIEW_FAILED) {
                    return;
                }
            }
        }

        /**
         * 只允许某一些状态发送通知
         *
         * 当 cash_type 为0时发送通知，为1时需要等微信异步回调
         * 提现转账类型：0：人工转账， 1：企业零钱付款
         */
        $allowSend = UserWalletCash::notificationByWhich('', function ($call) {
            return $call['send'];
        });

        if (in_array($cash->cash_status, $allowSend)) {
            $build = [
                'cash_actual_amount' => $cash->cash_actual_amount, // 提现实际到账金额
                'cash_status' => $cash->cash_status, // 提现结果
                'created_at' => $cash->created_at,  // 提现时间
                'refuse' => $cash->remark, // 原因
            ];

            // Tag 发送通知
            $cash->user->notify(new Withdrawal($cash->user, $cash, $build));
        }
    }
}
