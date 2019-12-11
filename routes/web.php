<?php

use App\Install\Controller as InstallController;

$route->get('/install', 'install.index', InstallController\IndexController::class);
$route->post('/install', 'install', InstallController\InstallController::class);

