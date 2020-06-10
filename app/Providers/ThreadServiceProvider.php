<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Providers;

use App\Listeners\Thread\ThreadListener;
use App\Listeners\Thread\ThreadVideoListener;
use App\Policies\ThreadPolicy;
use Discuz\Foundation\AbstractServiceProvider;

class ThreadServiceProvider extends AbstractServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function register()
    {
    }

    /**
     * @return void
     */
    public function boot()
    {
        $events = $this->app->make('events');

        $events->subscribe(ThreadListener::class);
        $events->subscribe(ThreadVideoListener::class);
        $events->subscribe(ThreadPolicy::class);
    }
}
