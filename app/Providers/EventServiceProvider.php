<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Providers;

use App\Events\Group\Saving;
use App\Events\Users\Logind;
use App\Events\Users\Logining;
use App\Events\Users\Registered;
use App\Events\Users\UserVerify;
use App\Listeners\Group\ChangeDefaultGroup;
use App\Listeners\User\ChangeLastActived;
use App\Listeners\User\ChckoutSite;
use App\Listeners\User\CheckLogin;
use App\Listeners\User\InviteBind;
use App\Listeners\User\MobileBind;
use App\Listeners\User\WeixinBind;
use App\Listeners\Wallet\CashReviewSubscriber;
use App\Listeners\Wallet\CreateUserWalletListener;
use App\Policies\AttachmentPolicy;
use App\Policies\GroupPolicy;
use App\Policies\InvitePolicy;
use App\Policies\StopWordPolicy;
use App\Policies\UserPolicy;
use App\Policies\UserWalletCashPolicy;
use App\Policies\UserWalletLogsPolicy;
use App\Policies\UserWalletPolicy;
use Discuz\Foundation\Suppor\Providers\EventServiceProvider as BaseEventServiceProvider;

class EventServiceProvider extends BaseEventServiceProvider
{
    protected $listen = [
        UserVerify::class => [
            WeixinBind::class,
            MobileBind::class
        ],
        Registered::class => [
            InviteBind::class,
            CreateUserWalletListener::class
        ],
        Logining::class => [
            CheckLogin::class
        ],
        Logind::class => [
            ChckoutSite::class,
            ChangeLastActived::class
        ],
        Saving::class => [
            ChangeDefaultGroup::class
        ]
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
        CashReviewSubscriber::class
    ];
}
