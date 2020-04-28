<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Providers;

use App\Formatter\Formatter;
use App\Models\DialogMessage;
use Discuz\Foundation\AbstractServiceProvider;

class DialogMessageServiceProvider extends AbstractServiceProvider
{
    /**
     * 注册服务.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * 引导服务.
     *
     * @return void
     */
    public function boot()
    {
        DialogMessage::setFormatter($this->app->make(Formatter::class));
    }
}
