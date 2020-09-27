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

use App\Events\DenyUsers\Saved as DenyUserSaved;
use App\Events\Group\Created as GroupCreated;
use App\Events\Group\Deleted as GroupDeleted;
use App\Events\Group\PaidGroup;
use App\Events\Group\Saving as GroupSaving;
use App\Events\Users\Logind;
use App\Events\Users\Logining;
use App\Events\Users\RefreshTokend;
use App\Events\Users\Registered;
use App\Events\Users\RegisteredCheck;
use App\Listeners\AddApiMiddleware;
use App\Listeners\DenyUser\DeleteFollow;
use App\Listeners\Group\ChangeDefaultGroup;
use App\Listeners\Group\PaidGroupOrder;
use App\Listeners\Group\ResetDefaultGroup;
use App\Listeners\Group\SetDefaultPermission;
use App\Listeners\Post\ReplaceContentAttachUrl;
use App\Listeners\User\AddDefaultGroup;
use App\Listeners\User\BanLogin;
use App\Listeners\User\ChangeLastActived;
use App\Listeners\User\CheckLogin;
use App\Listeners\User\CheckoutSite;
use App\Listeners\User\InviteBind;
use App\Listeners\User\Notifications;
use App\Listeners\User\ValidateLogin;
use App\Listeners\Wallet\CashReviewSubscriber;
use App\Listeners\Wallet\CreateUserWalletListener;
use App\Policies\AttachmentPolicy;
use App\Policies\GroupPolicy;
use App\Policies\InvitePolicy;
use App\Policies\StopWordPolicy;
use App\Policies\UserPolicy;
use App\Policies\UserWalletCashPolicy;
use App\Policies\UserWalletLogsPolicy;
use App\Policies\GroupPaidUserPolicy;
use App\Policies\UserWalletPolicy;
use Discuz\Api\Events\ConfigMiddleware;
use Discuz\Api\Events\WillSerializeData;
use Discuz\Foundation\Support\Providers\EventServiceProvider as BaseEventServiceProvider;

class EventServiceProvider extends BaseEventServiceProvider
{
    protected $listen = [
        Registered::class => [
            CreateUserWalletListener::class,
            InviteBind::class,
            AddDefaultGroup::class,
            Notifications::class
        ],
        RegisteredCheck::class => [
            BanLogin::class,
            ValidateLogin::class,
            ChangeLastActived::class
        ],
        // 登录后事件监听
        Logining::class => [
            CheckLogin::class
        ],
        // 登录事件监听
        Logind::class => [
            BanLogin::class,
            ValidateLogin::class,
            CheckoutSite::class,
            ChangeLastActived::class
        ],
        RefreshTokend::class => [
            ChangeLastActived::class
        ],
        GroupCreated::class => [
            SetDefaultPermission::class
        ],
        GroupSaving::class => [
            ChangeDefaultGroup::class
        ],
        GroupDeleted::class => [
            ResetDefaultGroup::class
        ],
        ConfigMiddleware::class => [
            AddApiMiddleware::class
        ],
        DenyUserSaved::class => [
            DeleteFollow::class
        ],
        PaidGroup::class => [
            PaidGroupOrder::class
        ],
        WillSerializeData::class => [
            ReplaceContentAttachUrl::class,
        ],
    ];

    protected $subscribe = [
        AttachmentPolicy::class,
        GroupPolicy::class,
        StopWordPolicy::class,
        UserPolicy::class,
        InvitePolicy::class,
        UserWalletPolicy::class,
        UserWalletLogsPolicy::class,
        UserWalletCashPolicy::class,
        CashReviewSubscriber::class,
        GroupPaidUserPolicy::class,
    ];
}
