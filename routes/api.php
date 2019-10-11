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
/*
|--------------------------------------------------------------------------
| Threads
|--------------------------------------------------------------------------
*/
$route->get('/threads', 'threads.index', ApiController\Threads\ListThreadsController::class);
$route->get('/threads/{id}', 'threads.resource', ApiController\Threads\ResourceThreadController::class);
$route->post('/threads', 'threads.create', ApiController\Threads\CreateThreadController::class);
$route->patch('/threads/{id}', 'threads.update', ApiController\Threads\UpdateThreadController::class);
$route->delete('/threads/{id}', 'threads.delete', ApiController\Threads\DeleteThreadController::class);
/*
|--------------------------------------------------------------------------
| StopWords
|--------------------------------------------------------------------------
*/
$route->get('/stop-words', 'stop-words.index', ApiController\StopWords\ListStopWordsController::class);
$route->get('/stop-words/{id}', 'stop-words.resource', ApiController\StopWords\ResourceStopWordController::class);
$route->post('/stop-words', 'stop-words.create', ApiController\StopWords\CreateStopWordController::class);
$route->patch('/stop-words/{id}', 'stop-words.update', ApiController\StopWords\UpdateStopWordController::class);
$route->delete('/stop-words/{id}', 'stop-words.delete', ApiController\StopWords\DeleteStopWordController::class);


/*
 |--------------------------------------------------------------------------
 | Payment
 |--------------------------------------------------------------------------
 */
$route->post('/pay/notify', 'pay.notify', ApiController\PayNotifyController::class);
$route->post('/pay/order', 'pay.order', ApiController\PayOrderController::class);
$route->get('/pay/qrcode', 'pay.qrcode', ApiController\PayQrcodeController::class);
$route->get('/pay/qr/img', 'pay.qr.img', ApiController\PayQrImgController::class);
$route->get('/pay/test', 'pay.test', ApiController\PayTestController::class);

