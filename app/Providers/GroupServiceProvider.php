<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Providers;

use App\Policies\GroupPolicy;
use Carbon\Laravel\ServiceProvider;

class GroupServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $events = $this->app->make('events');
        $events->subscribe(GroupPolicy::class);
    }
}
