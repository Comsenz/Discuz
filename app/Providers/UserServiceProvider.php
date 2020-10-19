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

namespace App\Providers;

use App\Listeners\User\UserListener;
use App\Listeners\Wallet\WalletListener;
use App\Models\User;
use App\Models\UserWalletCash;
use App\Models\UserWechat;
use App\Observer\UserObserver;
use App\Observer\UserWalletCashObserver;
use App\Observer\UserWechatObserver;
use Discuz\Foundation\AbstractServiceProvider;

class UserServiceProvider extends AbstractServiceProvider
{
    public function boot()
    {
        $events = $this->app->make('events');

        $events->subscribe(UserListener::class);
        $events->subscribe(WalletListener::class);

        User::observe(UserObserver::class);
        UserWechat::observe(UserWechatObserver::class);
        UserWalletCash::observe(UserWalletCashObserver::class);
    }
}
