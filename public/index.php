<?php

$uri = $_SERVER['REQUEST_URI'];
if (substr($uri, 0, 4) === '/api' || substr($uri, 0, 8) === '/install') {
    define('DISCUZ_START', microtime(true));
    require __DIR__ . '/../vendor/autoload.php';
    $app = new Discuz\Foundation\Application(dirname(__DIR__));
    $app->singleton(Discuz\Http\Server::class, Discuz\Http\Server::class);
    $app->make(Discuz\Http\Server::class)->listen();
    return;
}

$static_file = __DIR__ . $uri;

if (is_file($static_file)) {
    header('Content-Type: ' . mime_content_type($static_file));
    readfile($static_file);
    return;
}

header('Content-Type: text/html');
readfile(__DIR__ . '/index.html');