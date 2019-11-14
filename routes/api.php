<?php

use App\Api\Controller as ApiController;

/*
|--------------------------------------------------------------------------
| Site
|--------------------------------------------------------------------------
*/
$route->post('/settings', 'settings', ApiController\Settings\SetSettingsController::class);
$route->get('/settings', 'settings.list', ApiController\Settings\ListSettingsController::class);
$route->get('/siteinfo', 'site.info', ApiController\SiteInfoController::class);
$route->get('/check', 'check', ApiController\CheckController::class);

/*
|--------------------------------------------------------------------------
| Cloud APIs
|--------------------------------------------------------------------------
*/

$route->post('/qcloud/version', 'qcloud.version', ApiController\Qcloud\VersionController::class);


/*
|--------------------------------------------------------------------------
| Groups
|--------------------------------------------------------------------------
*/
$route->get('/groups', 'groups.list', ApiController\Group\ListGroupsController::class);
$route->get('/groups/{id}', 'groups.resource', ApiController\Group\ResourceGroupsController::class);
$route->post('/groups', 'group.create', ApiController\Group\CreateGroupController::class);

/*
|--------------------------------------------------------------------------
| Users
|--------------------------------------------------------------------------
*/
$route->get('/users', 'users.list', ApiController\ListUsersController::class);
$route->post('/users', 'users.create', ApiController\Users\CreateUserController::class);
$route->post('/login', 'users.login', ApiController\Users\LoginController::class);
$route->get('/userslist', 'users.list', ApiController\Users\ListUsersController::class);
$route->patch('/updatepwd', '', ApiController\Users\UpdatePwdUsersController::class);
$route->post('/access', 'access', ApiController\Users\AccessTockenController::class);
$route->get('/users/{id}', 'user.profile', ApiController\Users\UserProfileController::class);
$route->patch('/user/{id}', 'userprofile.update', ApiController\Users\UpdateUserProfileController::class);
$route->patch('/users', 'userpatch.update', ApiController\Users\UpdateUsersController::class);
$route->delete('/users', 'userpatch.delete', ApiController\Users\DeleteUsersController::class);
$route->post('/send', 'send', ApiController\Mobile\SendController::class);
$route->post('/get-message', 'send', ApiController\Mobile\MessageBindingController::class);
$route->post('/old-send', 'send', ApiController\Mobile\SendOldController::class);
$route->post('/message', 'send', ApiController\Mobile\GetMessageController::class);
$route->post('/message-login', 'send', ApiController\Mobile\LoginMessageController::class);
$route->post('/pwd-message', 'send', ApiController\Mobile\PwdMessageController::class);

/*
|--------------------------------------------------------------------------
| Threads
|--------------------------------------------------------------------------
*/

$route->get('/threads', 'threads.index', ApiController\Threads\ListThreadsController::class);
$route->get('/threads/{id}', 'threads.resource', ApiController\Threads\ResourceThreadController::class);
$route->post('/threads', 'threads.create', ApiController\Threads\CreateThreadController::class);
$route->patch('/threads/batch', 'threads.batch', ApiController\Threads\BatchUpdateThreadsController::class);
$route->patch('/threads/{id}', 'threads.update', ApiController\Threads\UpdateThreadController::class);
$route->delete('/threads', 'threads.delete', ApiController\Threads\DeleteThreadController::class);
$route->delete('/threads/{id}', 'threads.delete', ApiController\Threads\DeleteThreadController::class);

/*
|--------------------------------------------------------------------------
| Posts
|--------------------------------------------------------------------------
*/

$route->get('/posts', 'posts.index', ApiController\Posts\ListPostsController::class);
$route->post('/posts', 'posts.create', ApiController\Posts\CreatePostController::class);
$route->patch('/posts/batch', 'posts.batch', ApiController\Posts\BatchUpdatePostsController::class);
$route->patch('/posts/{id}', 'posts.update', ApiController\Posts\UpdatePostController::class);
$route->delete('/posts', 'posts.delete', ApiController\Posts\DeletePostController::class);
$route->delete('/posts/{id}', 'posts.delete', ApiController\Posts\DeletePostController::class);

/*
|--------------------------------------------------------------------------
| StopWords
|--------------------------------------------------------------------------
*/

$route->get('/stop-words', 'stop-words.index', ApiController\StopWords\ListStopWordsController::class);
$route->get('/stop-words/{id}', 'stop-words.resource', ApiController\StopWords\ResourceStopWordController::class);
$route->post('/stop-words', 'stop-words.create', ApiController\StopWords\CreateStopWordController::class);
$route->post('/stop-words/batch', 'stop-words.create', ApiController\StopWords\BatchCreateStopWordController::class);
$route->patch('/stop-words/{id}', 'stop-words.update', ApiController\StopWords\UpdateStopWordController::class);
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
 | Payments settings
 |--------------------------------------------------------------------------
 */
$route->get('/payments', 'payment.list', ApiController\Payment\ListPaymentsController::class);

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
$route->get('/classify', 'classify.list', ApiController\Classify\ListClassifyController::class);
$route->get('/classify/{id}', 'classify.resource', ApiController\Classify\ResourceClassifyController::class);
$route->post('/classify', 'classify.create', ApiController\Classify\CreateClassifyController::class);
$route->patch('/classify/{id}', 'classify.update', ApiController\Classify\UpdateClassifyController::class);
$route->delete('/classify/{id}', 'classify.delete', ApiController\Classify\DeleteClassifyController::class);

/*
|--------------------------------------------------------------------------
| Attachment
|--------------------------------------------------------------------------
*/
$route->post('/attachment', 'attachment.create', ApiController\Attachment\CreateAttachmentController::class);

/*
 |--------------------------------------------------------------------------
 | Order
 |--------------------------------------------------------------------------
 */
$route->get('/order/{order_sn}', 'order.resource', ApiController\Order\ResourceOrderController::class);
$route->post('/order', 'order.create', ApiController\Order\CreateOrderController::class);
$route->get('/order', 'order.list', ApiController\Order\ListOrderController::class);

/*
 |--------------------------------------------------------------------------
 | Trade
 |--------------------------------------------------------------------------
 */
$route->post('/trade/notify/wechat', 'trade.notify.wechat', ApiController\Trade\Notify\WechatNotifyController::class);
$route->post('/trade/pay/order/{order_sn}', 'trade.pay.order', ApiController\Trade\PayOrderController::class);

/*
 |--------------------------------------------------------------------------
 | Wallet
 |--------------------------------------------------------------------------
 */
$route->post('/wallet/user', 'wallet.user.create', ApiController\Wallet\CreateUserWalletController::class);
$route->get('/wallet/user/{user_id}', 'wallet.user.resource', ApiController\Wallet\ResourceUserWalletController::class);
$route->patch('/wallet/{wallet_id}', 'wallet.update', ApiController\Wallet\UpdateUserWalletController::class);
$route->post('/wallet/user/cash', 'wallet.user.cash.create', ApiController\Wallet\CreateCashUserWalletController::class);

/*
|--------------------------------------------------------------------------
| GroupPermission
|--------------------------------------------------------------------------
*/
$route->patch('/group-permission/{id}', 'groupPermission.update', ApiController\GroupPermission\UpdateGroupPermissionController::class);

/*
|--------------------------------------------------------------------------
| Notification
|--------------------------------------------------------------------------
*/
$route->get('/notification', 'notification.list', ApiController\Notification\ListNotificationController::class);
$route->get('/notification/{id}', 'notification.resource', ApiController\Notification\ResourceNotificationController::class);
$route->delete('/notification/{id}', 'notification.delete', ApiController\Notification\DeleteNotificationController::class);
