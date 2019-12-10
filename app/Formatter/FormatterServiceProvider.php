<?php

namespace App\Formatter;

use Discuz\Foundation\AbstractServiceProvider;
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
                $container->make('cache'),
                $this->app->storagePath().'/formatter'
            );
        });
    }
}
