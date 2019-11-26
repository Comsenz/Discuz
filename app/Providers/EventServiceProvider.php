<?php

namespace App\Providers;

use App\Events\Users\Registered;
use App\Events\Users\UserVerify;
use App\Listeners\User\InviteBind;
use App\Listeners\User\MobileBind;
use App\Listeners\User\WeixinBind;
use App\Policies\GroupPolicy;
use App\Policies\StopWordPolicy;
use App\Policies\UserPolicy;
use Discuz\Foundation\Suppor\Providers\EventServiceProvider as BaseEventServiceProvider;

class EventServiceProvider extends BaseEventServiceProvider
{
    protected $listen = [
        'App\Events\Users\Registered' => ['App\Listeners\Wallet\CreateUserWalletListener'],
        UserVerify::class => [
            WeixinBind::class,
            MobileBind::class
        ],
        Registered::class => [
            InviteBind::class
        ],
    ];

    protected $subscribe = [
        GroupPolicy::class,
        StopWordPolicy::class,
        UserPolicy::class,
        'App\Listeners\Wallet\CashReviewSubscriber',
        'App\Listeners\Order\OrderSubscriber',
    ];
}
