<?php


namespace Discuz\Foundation;


use Discuz\Web\WebServiceProvider;

class Site implements SiteInterface
{
    protected $basePath;

    public function __construct($basePath = null)
    {
        $this->basePath = $basePath;
    }

    /**
     * @return AppInterface|SiteApp
     */
    public function bootApp() {
        return new SiteApp($this->bootLaravel());
    }

    protected function bootLaravel() {
        $laravel = new Application($this->basePath);

        $laravel->register(WebServiceProvider::class);
//        $laravel->register()

        $laravel->boot();
        return $laravel;
    }
}
