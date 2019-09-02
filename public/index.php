<?php declare(strict_types = 1);

define('DISCUZ_START', microtime(true));

require __DIR__.'/../vendor/autoload.php';


(new \Discuz\Http\Server(
    new \App\Http\Site(dirname(__DIR__))
))->listen();


$m = function_exists('memory_get_usage') ? number_format(memory_get_usage()) : '';
$mt = function_exists('memory_get_peak_usage') ? number_format(memory_get_peak_usage()) : '';

dd(get_included_files(), microtime(true)-DISCUZ_START, $m, $mt, \Illuminate\Container\Container::getInstance());
