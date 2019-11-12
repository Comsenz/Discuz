<?php

namespace App\Providers;

use App\Policies\GroupPolicy;
use App\Policies\StopWordPolicy;
use Discuz\Foundation\Suppor\Providers\EventServiceProvider as BaseEventServiceProvider;

class EventServiceProvider extends BaseEventServiceProvider
{
    protected $listen = [
        App\Events\Users\Saving::class => [App\Listeners\Wallet\CreateUserWalletListner::class],
        App\Events\Wallet\Cash::class => [App\Listeners\Wallet\CashUserWalletListner::class],
    ];

    protected $subscribe = [
        GroupPolicy::class,
        StopWordPolicy::class,
    ];
}
