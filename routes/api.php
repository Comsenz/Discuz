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

$route->get('/forum', 'forum.settings', ApiController\Settings\ForumSettingsController::class);

$route->get('/test', 'test', ApiController\TestController::class);

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
$route->delete('/groups/{id}', 'group.delete', ApiController\Group\DeleteGroupController::class);

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
$route->get('/users/{id}', 'user.resource', ApiController\Users\ProfileController::class);
$route->patch('/users/{id}', 'user.update', ApiController\Users\UpdateUserController::class);
$route->patch('/users', 'users.update', ApiController\Users\UpdateUsersController::class);
$route->delete('/users/{id}', 'user.delete', ApiController\Users\DeleteUserController::class);
$route->delete('/users', 'users.delete', ApiController\Users\DeleteUsersController::class);
$route->post('/users/{id}/avatar', 'user.upload.avatar', ApiController\Users\UploadAvatarController::class);
$route->delete('/users/{id}/avatar', 'delete.avatar', ApiController\Users\DeleteAvatarController::class);
$route->get('/user/export', 'user.export', ApiController\Users\ExportUserController::class);

/*
|--------------------------------------------------------------------------
| Sms
|--------------------------------------------------------------------------
*/

$route->post('/sms/send', 'sms.send', ApiController\Mobile\SendController::class);
$route->post('/sms/verify', 'sms.verify', ApiController\Mobile\VerifyController::class);

$route->post('/get-message', 'send', ApiController\Mobile\MessageBindingController::class);
$route->post('/message', 'send', ApiController\Mobile\GetMessageController::class);
$route->post('/message-login', 'send', ApiController\Mobile\LoginMessageController::class);
$route->post('/pwd-message', 'send', ApiController\Mobile\PwdMessageController::class);

/*
|--------------------------------------------------------------------------
| Categories
|--------------------------------------------------------------------------
*/

$route->get('/categories', 'categories.index', ApiController\Category\ListCategoriesController::class);
$route->post('/categories', 'categories.create', ApiController\Category\CreateCategoryController::class);
$route->post('/categories/batch', 'categories.batchCreate', ApiController\Category\BatchCreateCategoriesController::class);
$route->patch('/categories/batch', 'categories.batchUpdate', ApiController\Category\BatchUpdateCategoriesController::class);
$route->patch('/categories/{id}', 'categories.update', ApiController\Category\UpdateCategoryController::class);
$route->delete('/categories/batch/{ids}', 'categories.batchDelete', ApiController\Category\BatchDeleteCategoriesController::class);
$route->delete('/categories/{id}', 'categories.delete', ApiController\Category\DeleteCategoryController::class);

/*
|--------------------------------------------------------------------------
| Threads
|--------------------------------------------------------------------------
*/

$route->get('/favorites', 'favorites', ApiController\Threads\ListFavoritesController::class);
$route->get('/threads', 'threads.index', ApiController\Threads\ListThreadsController::class);
$route->get('/threads/{id}', 'threads.resource', ApiController\Threads\ResourceThreadController::class);
$route->post('/threads', 'threads.create', ApiController\Threads\CreateThreadController::class);
$route->patch('/threads/batch', 'threads.batchUpdate', ApiController\Threads\BatchUpdateThreadsController::class);
$route->patch('/threads/{id}', 'threads.update', ApiController\Threads\UpdateThreadController::class);
$route->delete('/threads/batch/{ids}', 'threads.batchDelete', ApiController\Threads\BatchDeleteThreadsController::class);
$route->delete('/threads/{id}', 'threads.delete', ApiController\Threads\DeleteThreadController::class);

/*
|--------------------------------------------------------------------------
| Posts
|--------------------------------------------------------------------------
*/

$route->get('/likes', 'likes', ApiController\Posts\ListLikesController::class);
$route->get('/posts', 'posts.index', ApiController\Posts\ListPostsController::class);
$route->post('/posts', 'posts.create', ApiController\Posts\CreatePostController::class);
$route->patch('/posts/batch', 'posts.batchUpdate', ApiController\Posts\BatchUpdatePostsController::class);
$route->patch('/posts/{id}', 'posts.update', ApiController\Posts\UpdatePostController::class);
$route->delete('/posts/batch/{ids}', 'posts.batchDelete', ApiController\Posts\BatchDeletePostsController::class);
$route->delete('/posts/{id}', 'posts.delete', ApiController\Posts\DeletePostController::class);

/*
|--------------------------------------------------------------------------
| StopWords
|--------------------------------------------------------------------------
*/

$route->get('/stop-words', 'stop-words.index', ApiController\StopWords\ListStopWordsController::class);
$route->get('/stop-words/export', 'stop-words.export', ApiController\StopWords\ExportStopWordsController::class);
$route->get('/stop-words/{id}', 'stop-words.resource', ApiController\StopWords\ResourceStopWordController::class);
$route->post('/stop-words', 'stop-words.create', ApiController\StopWords\CreateStopWordController::class);
$route->post('/stop-words/batch', 'stop-words.batchCreate', ApiController\StopWords\BatchCreateStopWordsController::class);
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
$route->post('/invites', 'invites.create', ApiController\Invite\CreateAdminInviteController::class);
$route->patch('/invites/{id}', 'invites.update', ApiController\Invite\UpdateInviteController::class);
$route->delete('/invites/{id}', 'invites.delete', ApiController\Invite\DeleteInviteController::class);

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
$route->get('/userInviteCode', 'invite.userInviteCode', ApiController\Invite\UserInviteCodeController::class);
$route->post('/invite', 'invite.create.admin', ApiController\Invite\CreateAdminInviteController::class);
$route->delete('/invite/{id}', 'invite.delete', ApiController\Invite\DeleteInviteController::class);

/*
|--------------------------------------------------------------------------
| Emoji
|--------------------------------------------------------------------------
*/

$route->get('/emojiLoad', 'emoji.load', ApiController\Emoji\AutoloadEmojiController::class);
$route->get('/emoji', 'emoji.list', ApiController\Emoji\ListEmojiController::class);
