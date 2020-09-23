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

namespace App\Notifications;

use App\Models\UserWalletCash;
use Illuminate\Bus\Queueable;

/**
 * 提现通知
 *
 * Class Withdrawal
 * @package App\Notifications
 */
class Withdrawal extends System
{
    use Queueable;

    public $cash;

    public $channel;

    /**
     * Withdrawal constructor.
     *
     * @param UserWalletCash $cash
     * @param string $messageClass
     * @param array $build
     */
    public function __construct(UserWalletCash $cash, $messageClass = '', $build = [])
    {
        $this->setChannelName($messageClass);

        $this->cash = $cash;

        parent::__construct($messageClass, $build);
    }

    /**
     * 数据库驱动通知
     *
     * @param $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return [
            'user_id' => $this->cash->user->id,  // 提现人ID
            'wallet_cash_id' => $this->cash->id, // 提现记录ID
            'cash_actual_amount' => $this->cash->cash_actual_amount, // 实际提现金额
            'cash_apply_amount' => $this->cash->cash_apply_amount,   // 提现申请金额
            'cash_status' => $this->cash->cash_status,
            'remark' => $this->cash->remark,
            'created_at' => $this->cash->formatDate('created_at'),
        ];
    }

    /**
     * 设置频道名称
     *
     * @param $strClass
     */
    protected function setChannelName($strClass)
    {
        switch ($strClass) {
            case 'App\MessageTemplate\Wechat\WechatWithdrawalMessage':
                $this->channel = 'wechat';
                break;
            case 'App\MessageTemplate\WithdrawalMessage':
            default:
                $this->channel = 'database';
                break;
        }
    }
}
