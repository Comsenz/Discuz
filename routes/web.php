<?php

use App\Install\Controller as InstallController;

$route->get('/install', 'install.index', InstallController\IndexController::class);
$route->post('/install', 'install', InstallController\InstallController::class);

$route->get('/', 'index', \App\Http\Controller\IndexController::class);
$route->get('/d/{id}', 'discussion', \App\Http\Controller\DiscussionController::class);

