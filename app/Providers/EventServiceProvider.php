<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Providers;

use App\Events\Group\Created as GroupCreated;
use App\Events\Group\Saving as GroupSaving;
use App\Events\Users\Logind;
use App\Events\Users\Logining;
use App\Events\Users\RefreshTokend;
use App\Events\Users\Registered;
use App\Events\Users\UserVerify;
use App\Listeners\Group\ChangeDefaultGroup;
use App\Listeners\Group\SetDefaultPermission;
use App\Listeners\User\BanLogin;
use App\Listeners\User\ChangeLastActived;
use App\Listeners\User\ChckoutSite;
use App\Listeners\User\CheckLogin;
use App\Listeners\User\InviteBind;
use App\Listeners\User\MobileBind;
use App\Listeners\User\ValidateLogin;
use App\Listeners\User\WechatBind;
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
            WechatBind::class,
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
            BanLogin::class,
            ValidateLogin::class,
            ChckoutSite::class,
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
