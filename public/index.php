<?php

define('DISCUZ_START', microtime(true));

require __DIR__.'/../vendor/autoload.php';

$app = new Discuz\Foundation\Application(dirname(__DIR__));

$app->singleton(Discuz\Http\Server::class, Discuz\Http\Server::class);

$app->make(Discuz\Http\Server::class)->listen();
