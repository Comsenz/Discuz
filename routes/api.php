<?php

use App\Api\Controller as ApiController;

$route->get('/users', 'users.list', ApiController\ListUsersController::class);
$route->post('/add', '', ApiController\Users\AddUsersController::class);
$route->post('/login', '', ApiController\Users\LoginUsersController::class);

$route->get('/circles', 'circles.list', ApiController\Circle\ListCircleController::class);
$route->post('/circles', 'circles.create', ApiController\Circle\CreateCircleController::class);
$route->patch('/circles', 'circles.update', ApiController\Circle\UpdateCircleController::class);
$route->delete('/circles', 'circles.delete', ApiController\Circle\DeleteCircleController::class);

$route->post('/settings', 'settings', ApiController\SetSettingsController::class);
