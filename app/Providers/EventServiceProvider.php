<?php

namespace App\Providers;

use App\Policies\GroupPolicy;
use App\Policies\StopWordPolicy;
use Discuz\Foundation\Suppor\Providers\EventServiceProvider as BaseEventServiceProvider;

class EventServiceProvider extends BaseEventServiceProvider
{
    protected $listen = [
        'App\Events\Users\Registered' => ['App\Listeners\Wallet\CreateUserWalletListener']
    ];

    protected $subscribe = [
        GroupPolicy::class,
        StopWordPolicy::class,
        'App\Listeners\Wallet\ReviewCashSubscriber',
    ];
}
