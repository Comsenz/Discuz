<?php

namespace App\Providers;

use App\Events\Users\Saving;
use App\Listeners\CreateUserWalletListner;
use App\Policies\GroupPolicy;
use App\Policies\StopWordPolicy;
use Discuz\Foundation\Suppor\Providers\EventServiceProvider as BaseEventServiceProvider;

class EventServiceProvider extends BaseEventServiceProvider
{

    protected $listen = [
        Saving::class => [CreateUserWalletListner::class],
    ];

    protected $subscribe = [
        GroupPolicy::class,
        StopWordPolicy::class,
    ];
}
