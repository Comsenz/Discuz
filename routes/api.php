<?php
use App\Api\Controller as ApiController;

$route->get('/users', 'users.list', ApiController\ListUsersController::class);
$route->get('/pay/order', 'pay.order', ApiController\PayOrderController::class);
$route->get('/pay/qrcode', 'pay.qrcode', ApiController\PayQrcodeController::class);
$route->get('/pay/qr/img', 'pay.qr.img', ApiController\PayQrImgController::class);
$route->get('/pay/test', 'pay.test', ApiController\PayTestController::class);
