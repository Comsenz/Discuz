<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Formatter;

use Discuz\Foundation\AbstractServiceProvider;
use Discuz\Http\UrlGenerator;
use Illuminate\Contracts\Container\Container;

class FormatterServiceProvider extends AbstractServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function register()
    {
        $this->app->singleton(Formatter::class, function (Container $container) {
            return new Formatter(
                $container->make(UrlGenerator::class),
                $container->make('cache'),
                $this->app->storagePath().'/formatter'
            );
        });
    }
}
