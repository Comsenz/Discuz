<?php

use App\Install\Controller as InstallController;

$route->get('/install', 'install.index', InstallController\IndexController::class);
$route->post('/install', 'install', InstallController\InstallController::class);

$route->get('/{other:.*}', 'other', \App\Http\Controller\IndexController::class);
