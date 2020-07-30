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

use App\MessageTemplate\Wechat\WechatWithdrawalMessage;
use App\MessageTemplate\WithdrawalMessage;
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
         * 只允许某一些状态发送通知
         */
        $allowSend = UserWalletCash::notificationByWhich('', function ($call) {
            return $call['send'];
        });

        if (in_array($cash->cash_status, $allowSend)) {
            $cash->user->notify(new Withdrawal($cash, WithdrawalMessage::class));

            $cash->user->notify(new Withdrawal($cash, WechatWithdrawalMessage::class, [
                'cash_actual_amount' => $cash->cash_actual_amount, // 提现实际到账金额
                'cash_status' => $cash->cash_status, // 提现结果
                'created_at' => $cash->created_at,  // 提现时间
                'refuse' => $cash->remark, // 原因
            ]));
        }
    }
}
