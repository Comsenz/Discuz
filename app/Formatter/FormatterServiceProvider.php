<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Formatter;

use Discuz\Cache\CacheManager;
use Discuz\Foundation\AbstractServiceProvider;
use Discuz\Foundation\Application;
use Discuz\Http\UrlGenerator;

class FormatterServiceProvider extends AbstractServiceProvider
{
    /**
     * @var UrlGenerator
     */
    protected $url;

    /**
     * @var CacheManager
     */
    protected $cache;

    /**
     * {@inheritdoc}
     */
    public function __construct(Application $app)
    {
        parent::__construct($app);

        $this->url = $app->make(UrlGenerator::class);
        $this->cache = $app->make('cache');
    }

    /**
     * {@inheritdoc}
     */
    public function register()
    {
        $this->app->singleton(Formatter::class, function () {
            return new Formatter(
                $this->url,
                $this->cache,
                $this->app->storagePath().'/formatter'
            );
        });

        $this->app->singleton(MarkdownFormatter::class, function () {
            return new MarkdownFormatter(
                $this->url,
                $this->cache,
                $this->app->storagePath().'/formatter'
            );
        });
    }
}
