<?php

use App\Api\Controller as ApiController;

/*
|--------------------------------------------------------------------------
| Site
|--------------------------------------------------------------------------
*/
$route->post('/settings', 'settings', ApiController\SetSettingsController::class);
$route->get('/siteinfo', 'siteinfo', ApiController\SiteInfoController::class);
$route->get('/groups', 'groups', ApiController\Group\ListGroupsController::class);
$route->post('/groups', 'group.create', ApiController\Group\CreateGroupController::class);

/*
|--------------------------------------------------------------------------
| Users
|--------------------------------------------------------------------------
*/
$route->get('/users', 'users.list', ApiController\ListUsersController::class);
$route->post('/users', 'users.add', ApiController\Users\AddUsersController::class);
$route->post('/login', 'users.login', ApiController\Users\LoginUsersController::class);
$route->get('/userslist', 'users.list', ApiController\Users\ListUsersController::class);
$route->patch('/updatepwd', '', ApiController\Users\UpdatePwdUsersController::class);
$route->post('/access', 'access', ApiController\Users\AccessTockenController::class);
$route->get('/users/{id}', 'user.profile', ApiController\Users\UserProfileController::class);
$route->patch('/user/{id}', 'userprofile.update', ApiController\Users\UpdateUserProfileController::class);
$route->patch('/users', 'userpatch.update', ApiController\Users\UpdateUsersController::class);
$route->delete('/users', 'userpatch.delete', ApiController\Users\DeleteUsersController::class);

/*
|--------------------------------------------------------------------------
| Threads
|--------------------------------------------------------------------------
*/

$route->get('/threads', 'threads.index', ApiController\Threads\ListThreadsController::class);
$route->get('/threads/{id}', 'threads.resource', ApiController\Threads\ResourceThreadController::class);
$route->post('/threads', 'threads.create', ApiController\Threads\CreateThreadController::class);
$route->patch('/threads/batch', 'threads.batch', ApiController\Threads\BatchThreadController::class);
$route->patch('/threads/{id}', 'threads.update', ApiController\Threads\UpdateThreadController::class);
$route->delete('/threads/{id}', 'threads.delete', ApiController\Threads\DeleteThreadController::class);

/*
|--------------------------------------------------------------------------
| Posts
|--------------------------------------------------------------------------
*/

$route->post('/posts', 'posts.create', ApiController\Posts\CreatePostController::class);

/*
|--------------------------------------------------------------------------
| StopWords
|--------------------------------------------------------------------------
*/

$route->get('/stop-words', 'stop-words.index', ApiController\StopWords\ListStopWordsController::class);
$route->get('/stop-words/{id}', 'stop-words.resource', ApiController\StopWords\ResourceStopWordController::class);
$route->post('/stop-words', 'stop-words.create', ApiController\StopWords\CreateStopWordController::class);
$route->post('/stop-words/batch', 'stop-words.create', ApiController\StopWords\CreateStopWordController::class);
$route->patch('/stop-words/{id}', 'stop-words.update', ApiController\StopWords\UpdateStopWordController::class);
$route->delete('/stop-words', 'stop-words.delete', ApiController\StopWords\DeleteStopWordController::class);
$route->delete('/stop-words/{id}', 'stop-words.delete', ApiController\StopWords\DeleteStopWordController::class);

/*
 |--------------------------------------------------------------------------
 | Payment
 |--------------------------------------------------------------------------
 */

$route->post('/pay/notify', 'pay.notify', ApiController\Pay\PayNotifyController::class);
$route->post('/pay/order', 'pay.order', ApiController\Pay\PayOrderController::class);
$route->get('/pay/qrcode', 'pay.qrcode', ApiController\Pay\PayQrcodeController::class);
$route->get('/pay/qr/img', 'pay.qr.img', ApiController\Pay\PayQrImgController::class);
$route->get('/pay/test', 'pay.test', ApiController\Pay\PayTestController::class);

/*
|--------------------------------------------------------------------------
| Circles
|--------------------------------------------------------------------------
*/
$route->get('/circles', 'circles.list', ApiController\Circle\ListCircleController::class);
$route->post('/circles', 'circles.create', ApiController\Circle\CreateCircleController::class);
$route->patch('/circles/{id}', 'circles.update', ApiController\Circle\UpdateCircleController::class);
$route->delete('/circles/{id}', 'circles.delete', ApiController\Circle\DeleteCircleController::class);

/*
|--------------------------------------------------------------------------
| Invites
|--------------------------------------------------------------------------
*/
$route->get('/invites', 'invites.list', ApiController\Invite\ListInviteController::class);
$route->get('/invites/{id}', 'invites.resource', ApiController\Invite\ResourceInviteController::class);
$route->post('/invites', 'invites.create', ApiController\Invite\CreateInviteController::class);
$route->patch('/invites/{id}', 'invites.update', ApiController\Invite\UpdateInviteController::class);
$route->delete('/invites/{id}', 'invites.delete', ApiController\Invite\DeleteInviteController::class);

/*
|--------------------------------------------------------------------------
| Classify
|--------------------------------------------------------------------------
*/
$route->middleware(App\Api\Middleware\Authentication::class)
    ->get('/classify', 'classify.list', ApiController\Classify\ListClassifyController::class);
$route->middleware(App\Api\Middleware\Authentication::class)
    ->get('/classify/{id}', 'classify.resource', ApiController\Classify\ResourceClassifyController::class);
$route->middleware(App\Api\Middleware\Authentication::class)
    ->post('/classify', 'classify.create', ApiController\Classify\CreateClassifyController::class);
$route->middleware(App\Api\Middleware\Authentication::class)
    ->patch('/classify/{id}', 'classify.update', ApiController\Classify\UpdateClassifyController::class);
$route->middleware(App\Api\Middleware\Authentication::class)
    ->delete('/classify/{id}', 'classify.delete', ApiController\Classify\DeleteClassifyController::class);

/*
|--------------------------------------------------------------------------
| Attachment
|--------------------------------------------------------------------------
*/
$route->middleware(App\Api\Middleware\Authentication::class)
    ->post('/attachment', 'attachment.create', ApiController\Attachment\CreateAttachmentController::class);

/*
 |--------------------------------------------------------------------------
 | Order
 |--------------------------------------------------------------------------
 */
$route->get('/order/{order_sn}', 'order.resource', ApiController\Order\ResourceOrderController::class);
$route->post('/order', 'order.create', ApiController\Order\CreateOrderController::class);

/*
 |--------------------------------------------------------------------------
 | Trade
 |--------------------------------------------------------------------------
 */
$route->post('/trade/notify/wechat', 'trade.notify.wechat', ApiController\Trade\Notify\WechatNotifyController::class);
$route->post('/trade/pay/order/{order_sn}', 'trade.pay.order', ApiController\Trade\PayOrderController::class);
$route->post('/trade/query/order/{order_sn}', 'trade.query.order', ApiController\Trade\QueryOrderController::class);
