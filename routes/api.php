<?php

use App\Api\Controller as ApiController;

$route->get('/users', 'users.list', ApiController\ListUsersController::class);
$route->get('/circle', 'circle.list', ApiController\Circle\ListCircleController::class);
