<?php

use App\Api\Controller as ApiController;

/*
|--------------------------------------------------------------------------
| Site
|--------------------------------------------------------------------------
*/
$route->post('/settings', 'settings', ApiController\Settings\SetSettingsController::class);
$route->get('/settings', 'settings.list', ApiController\Settings\ListSettingsController::class);
$route->post('/settings/logo', 'settings.upload.logo', ApiController\Settings\UploadLogoController::class);
$route->delete('/settings/logo', 'settings.delete.logo', ApiController\Settings\DeleteLogoController::class);
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
| Auth
|--------------------------------------------------------------------------
*/

$route->post('/login', 'login', ApiController\Users\LoginController::class);
$route->post('/register', 'register', ApiController\Users\RegisterController::class);

/*
|--------------------------------------------------------------------------
| Oauth client
|--------------------------------------------------------------------------
*/

$route->get('/oauth/weixin', 'login', ApiController\Users\WeixinLoginController::class);

/*
|--------------------------------------------------------------------------
| Users
|--------------------------------------------------------------------------
*/

$route->get('/users', 'users.list', ApiController\Users\ListUsersController::class);
$route->post('/users', 'users.create', ApiController\Users\CreateUserController::class);
$route->get('/users/{id}', 'users.profile', ApiController\Users\ProfileController::class);
$route->patch('/users/{id}', 'users.update', ApiController\Users\UpdateProfileController::class);
//$route->patch('/users', 'userpatch.update', ApiController\Users\UpdateUsersController::class);
$route->delete('/users/{id}', 'users.delete', ApiController\Users\DeleteUsersController::class);

//$route->patch('/updatepwd', '', ApiController\Users\UpdatePwdUsersController::class);
//$route->post('/access', 'access', ApiController\Users\AccessTockenController::class);

$route->post('/users/{id}/avatar', 'users.upload.avatar', ApiController\Users\UploadAvatarController::class);
$route->delete('/users/{id}/avatar', 'delete.avatar', ApiController\Users\DeleteAvatarController::class);

/*
|--------------------------------------------------------------------------
| Sms
|--------------------------------------------------------------------------
*/
$route->post('/sms/send', 'sms.send', ApiController\Mobile\SendController::class);
$route->post('/sms/verify', 'sms.verify', ApiController\Mobile\VerifyController::class);

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

$route->get('/favorites', 'favorites', ApiController\Threads\ListFavoritesController::class);
$route->get('/threads', 'threads.index', ApiController\Threads\ListThreadsController::class);
$route->get('/threads/{id}', 'threads.resource', ApiController\Threads\ResourceThreadController::class);
$route->post('/threads', 'threads.create', ApiController\Threads\CreateThreadController::class);
$route->patch('/threads/batch', 'threads.batch', ApiController\Threads\BatchUpdateThreadsController::class);
$route->patch('/threads/{id}', 'threads.update', ApiController\Threads\UpdateThreadController::class);
$route->delete('/threads/batch/{ids}', 'threads.delete', ApiController\Threads\BatchDeleteThreadsController::class);
$route->delete('/threads/{id}', 'threads.delete', ApiController\Threads\DeleteThreadController::class);

/*
|--------------------------------------------------------------------------
| Posts
|--------------------------------------------------------------------------
*/

$route->get('/likes', 'likes', ApiController\Posts\ListLikesController::class);
$route->get('/posts', 'posts.index', ApiController\Posts\ListPostsController::class);
$route->post('/posts', 'posts.create', ApiController\Posts\CreatePostController::class);
$route->patch('/posts/batch', 'posts.batch', ApiController\Posts\BatchUpdatePostsController::class);
$route->patch('/posts/{id}', 'posts.update', ApiController\Posts\UpdatePostController::class);
$route->delete('/posts/batch/{ids}', 'posts.delete', ApiController\Posts\BatchDeletePostsController::class);
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
$route->get('/order', 'order.list', ApiController\Order\ListOrdersController::class);

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
$route->get('/wallet/user/{user_id}', 'wallet.user.resource', ApiController\Wallet\ResourceUserWalletController::class);
$route->patch('/wallet/user/{user_id}', 'wallet.user.update', ApiController\Wallet\UpdateUserWalletController::class);

$route->post('/wallet/cash', 'wallet.cash.create', ApiController\Wallet\CreateUserWalletCashController::class);
$route->get('/wallet/cash', 'wallet.cash.list', ApiController\Wallet\ListUserWalletCashController::class);
$route->post('/wallet/cash/review', 'wallet.cash.review', ApiController\Wallet\UserWalletCashReviewController::class);
$route->get('/wallet/log', 'wallet.log.list', ApiController\Wallet\ListUserWalletLogsController::class);
/*

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
$route->get('/notificationUnread', 'notification.unread', ApiController\Notification\UnreadNotificationController::class);
$route->get('/notification', 'notification.list', ApiController\Notification\ListNotificationController::class);
$route->get('/notification/{id}', 'notification.resource', ApiController\Notification\ResourceNotificationController::class);
$route->delete('/notification/{id}', 'notification.delete', ApiController\Notification\DeleteNotificationController::class);

/*
|--------------------------------------------------------------------------
| Invite
|--------------------------------------------------------------------------
*/
$route->get('/invite', 'invite.list', ApiController\Invite\ListInviteController::class);
$route->get('/invite/{id}', 'invite.resource', ApiController\Invite\ResourceInviteController::class);
$route->post('/invite', 'invite.create', ApiController\Invite\CreateInviteController::class);
$route->delete('/invite/{id}', 'invite.delete', ApiController\Invite\DeleteInviteController::class);

/*
|--------------------------------------------------------------------------
| Emoji
|--------------------------------------------------------------------------
*/
$route->get('/emojiLoad', 'emoji.load', ApiController\Emoji\AutoloadEmojiController::class);
$route->get('/emoji', 'emoji.list', ApiController\Emoji\ListEmojiController::class);

