<?php


namespace App\Providers;

use App\Policies\GroupPolicy;
use Discuz\Foundation\Suppor\Providers\EventServiceProvider as BaseEventServiceProvider;

class EventServiceProvider extends BaseEventServiceProvider
{

    protected $listen = [

    ];

    protected $subscribe = [
        GroupPolicy::class
    ];
}
