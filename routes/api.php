<?php

use App\Api\Controller as ApiController;

$route->get('/', ApiController\ListUsersController::class);
