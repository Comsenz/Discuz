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

namespace App\Listeners\Wallet;

use App\Events\Wallet\Saved;
use App\Listeners\WalletLog\SaveWalletLogToDatabase;
use Illuminate\Contracts\Events\Dispatcher;

class WalletListener
{
    public function subscribe(Dispatcher $events)
    {
        /**
         * 执行钱包操作时
         *
         * @see SaveWalletLogToDatabase 记录钱包日志
         * @see SendNotifyOfWalletChanges 发送金额变动通知 - 财务通知
         */
        $events->listen(Saved::class, SaveWalletLogToDatabase::class);
        $events->listen(Saved::class, SendNotifyOfWalletChanges::class);
    }
}
