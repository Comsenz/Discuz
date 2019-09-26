<?php

use App\Api\Controller as ApiController;

$route->get('/users', 'users.list', ApiController\ListUsersController::class);
$route->post('/add', '', ApiController\Users\AddUsersController::class);
$route->post('/login', '', ApiController\Users\LoginUsersController::class);

$route->get('/circle', 'circle.list', ApiController\Circle\ListCircleController::class);
$route->post('/circle/create', 'circle.create', ApiController\Circle\CreateCircleController::class);
$route->post('/circle/update', 'circle.update', ApiController\Circle\UpdateCircleController::class);
$route->post('/circle/delete', 'circle.delete', ApiController\Circle\DeleteCircleController::class);

$route->post('/settings', 'settings', ApiController\SetSettingsController::class);
