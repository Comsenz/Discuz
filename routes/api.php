<?php

/**
 * Copyright (C) 2020 Tencent Cloud.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

use App\Api\Controller as ApiController;

/*
|--------------------------------------------------------------------------
| uc client
|--------------------------------------------------------------------------
*/

$route->get('/uc', 'uc', ApiController\Ucenter\UcenterController::class);
$route->post('/uc/login', 'uc.login', ApiController\Ucenter\LoginController::class);

/*
|--------------------------------------------------------------------------
| Site Settings
|--------------------------------------------------------------------------
*/

$route->post('/settings', 'settings', ApiController\Settings\SetSettingsController::class);
$route->get('/settings', 'settings.list', ApiController\Settings\ListSettingsController::class);
$route->post('/settings/logo', 'settings.upload.logo', ApiController\Settings\UploadLogoController::class);
$route->delete('/settings/logo', 'settings.delete.logo', ApiController\Settings\DeleteLogoController::class);
$route->get('/siteinfo', 'site.info', ApiController\SiteInfoController::class);
$route->get('/check', 'check', ApiController\CheckController::class);
$route->get('/forum', 'forum.settings', ApiController\Settings\ForumSettingsController::class);

/*
|--------------------------------------------------------------------------
| Passport Settings
|--------------------------------------------------------------------------
*/

$route->get('/signature', 'signature', ApiController\Qcloud\CreateVodUploadSignatureController::class);

/*
|--------------------------------------------------------------------------
| Groups
|--------------------------------------------------------------------------
*/

$route->get('/groups/paid', 'groups.paid', ApiController\Group\ListPaidUserGroupsController::class);
$route->get('/groups', 'groups.list', ApiController\Group\ListGroupsController::class);
$route->get('/groups/{id}', 'groups.resource', ApiController\Group\ResourceGroupsController::class);
$route->post('/groups', 'groups.create', ApiController\Group\CreateGroupController::class);
$route->post('/groups/{id}/icon', 'groups.upload.icon', ApiController\Group\UploadIconController::class);
$route->patch('/groups/{id}', 'group.update', ApiController\Group\UpdateGroupController::class);
$route->patch('/groups', 'group.update', ApiController\Group\UpdateGroupsController::class);
$route->delete('/groups/{id}', 'group.delete', ApiController\Group\DeleteGroupController::class);
$route->delete('/groups', 'groups.delete', ApiController\Group\DeleteGroupsController::class);

/*
|--------------------------------------------------------------------------
| Permission
|--------------------------------------------------------------------------
*/

$route->post('/permission', 'permission.update', ApiController\Permission\UpdateGroupPermissionController::class);
$route->post('/permission/group', 'permission.group', ApiController\Permission\SetPermissionController::class);

/*
|--------------------------------------------------------------------------
| Auth
|--------------------------------------------------------------------------
*/

$route->post('/login', 'login', ApiController\Users\LoginController::class);
$route->post('/register', 'register', ApiController\Users\RegisterController::class);
$route->post('/refresh-token', 'oauth2.refresh.token', ApiController\Oauth2\RefreshTokenController::class);

/*
|--------------------------------------------------------------------------
| Oauth client
|--------------------------------------------------------------------------
*/

$route->get('/oauth/wechat', 'wechat.login', ApiController\Users\WechatLoginController::class); // 已弃用
$route->get('/oauth/wechat/user', 'wechat.user', ApiController\Users\WechatUserController::class);
$route->get('/oauth/wechat/pc/bind', 'wechat.pc.bind', ApiController\Users\WechatPcBindController::class);
$route->get('/oauth/wechat/pc/bind/{session_token}', 'wechat.pc.bind.poll', ApiController\Users\WechatPcBindPollController::class);
$route->get('/oauth/wechat/pc', 'wechat.web.login', ApiController\Users\WechatWebLoginController::class); // 已弃用
$route->get('/oauth/wechat/pc/user', 'wechat.pc.user', ApiController\Users\WechatWebUserController::class);
$route->get('/oauth/welink', 'welink.login', ApiController\Users\WelinkLoginController::class);
$route->get('/oauth/wechat/web/user', 'wechat.web.user', ApiController\Users\WechatWebUserLoginController::class);
$route->get('/oauth/wechat/web/user/event', 'wechat.web.user.event', ApiController\Users\WechatWebUserLoginEventController::class);
$route->post('/oauth/wechat/web/user/event', 'wechat.web.user.postevent', ApiController\Users\WechatWebUserLoginPostEventController::class);
$route->get('/oauth/wechat/web/user/search', 'wechat.web.user.search', ApiController\Users\WechatWebUserLoginSearchController::class);
$route->post('/oauth/wechat/miniprogram', 'wechat.miniprogram.login', ApiController\Users\WechatMiniProgramLoginController::class);
$route->get('/oauth/wechat/miniprogram/code', 'wechat.mini.program.code', ApiController\Wechat\WechatMiniProgramCodeController::class);
$route->get('/oauth/wechat/qy', 'wechat.qy.login', ApiController\Users\WechatQyLoginController::class);
$route->get('/oauth/wechat/qy/user', 'wechat.qy.user', ApiController\Users\WechatQyUserController::class);
$route->get('/oauth/qq', 'qq.login', ApiController\Users\QQLoginController::class);
$route->get('/oauth/qq/user', 'qq.user', ApiController\Users\QQUserController::class);
$route->get('/oauth/wechat/pc/qrcode', 'wechat.pc.qrcode', ApiController\Users\WechatPcQrCodeController::class);
$route->get('/oauth/wechat/pc/login/{session_token}', 'wechat.pc.login.poll', ApiController\Users\WechatPcLoginPollController::class);
$route->get('/oauth/wechat/qrcode/login/{session_token}', 'wechat.qrcode.login', ApiController\Users\WechatQrcodeLoginController::class);

/*
|--------------------------------------------------------------------------
| Users
|--------------------------------------------------------------------------
*/

$route->get('/users/recommended', 'user.recommended', ApiController\Users\RecommendedUserController::class);
$route->get('/users', 'users.list', ApiController\Users\ListUsersController::class);
$route->post('/users', 'users.create', ApiController\Users\CreateUserController::class);
$route->post('/users/pay-password/reset', '', ApiController\Users\ResetPayPasswordController::class);
$route->patch('/users/real', 'users.real', ApiController\Users\RealUserController::class);
$route->get('/users/{id}', 'user.resource', ApiController\Users\ProfileController::class);
$route->patch('/users/{id}', 'user.update', ApiController\Users\UpdateUserController::class);
$route->patch('/users', 'users.update', ApiController\Users\UpdateUsersController::class);
$route->delete('/users/{id}', 'user.delete', ApiController\Users\DeleteUserController::class);
$route->delete('/users', 'users.delete', ApiController\Users\DeleteUsersController::class);
$route->post('/users/{id}/avatar', 'user.upload.avatar', ApiController\Users\UploadAvatarController::class);
$route->get('/users/{id}/deny', 'user.deny.list', ApiController\Users\ListDenyUserController::class);
$route->post('/users/{id}/deny', 'user.deny', ApiController\Users\CreateDenyUserController::class);
$route->delete('/users/{id}/deny', 'user.delete.deny', ApiController\Users\DeleteDenyUserController::class);
$route->delete('/users/{id}/avatar', 'user.delete.avatar', ApiController\Users\DeleteAvatarController::class);
$route->delete('/users/{id}/wechat', 'user.delete.wechat', ApiController\Users\UnbindWechatController::class);
$route->get('/export/users', 'export.users', ApiController\Users\ExportUserController::class);

/*
|--------------------------------------------------------------------------
| Sms
|--------------------------------------------------------------------------
*/

$route->post('/sms/send', 'sms.send', ApiController\Mobile\SendController::class);
$route->post('/sms/verify', 'sms.verify', ApiController\Mobile\VerifyController::class);
$route->post('/mobile/bind/miniprogram', 'mobile.miniprogram', ApiController\Mobile\BindWchatMiniprogramMobileController::class);

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
$route->get('/threads/share/{id}', 'threads.share', ApiController\Threads\ShareThreadController::class);
$route->get('/threads/relate/{id}', 'threads.relate', ApiController\Threads\RelateThreadsController::class);
$route->get('/threads/likes', 'threads.likes', ApiController\Threads\ListLikesController::class);
$route->get('/threads/{id}', 'threads.resource', ApiController\Threads\ResourceThreadController::class);
$route->post('/threads', 'threads.create', ApiController\Threads\CreateThreadController::class);
$route->patch('/threads/batch', 'threads.batchUpdate', ApiController\Threads\BatchUpdateThreadsController::class);
$route->patch('/threads/{id}', 'threads.update', ApiController\Threads\UpdateThreadController::class);
$route->delete('/threads/batch/{ids}', 'threads.batchDelete', ApiController\Threads\BatchDeleteThreadsController::class);
$route->delete('/threads/{id}', 'threads.delete', ApiController\Threads\DeleteThreadController::class);
$route->post('/threads/notify/video', 'threads.notify.video', ApiController\Threads\Notify\ThreadVideoNotifyController::class);
$route->post('/thread/video', 'threads.video', ApiController\Threads\CreateThreadVideoController::class);

/*
|--------------------------------------------------------------------------
| Posts
|--------------------------------------------------------------------------
*/

$route->get('/likes', 'likes', ApiController\Posts\ListLikesController::class);
$route->get('/posts', 'posts.index', ApiController\Posts\ListPostsController::class);
$route->get('/posts/{id}', 'posts.resource', ApiController\Posts\ResourcePostController::class);
$route->post('/posts', 'posts.create', ApiController\Posts\CreatePostController::class);
$route->patch('/posts/batch', 'posts.batchUpdate', ApiController\Posts\BatchUpdatePostsController::class);
$route->patch('/posts/{id}', 'posts.update', ApiController\Posts\UpdatePostController::class);
$route->delete('/posts/batch/{ids}', 'posts.batchDelete', ApiController\Posts\BatchDeletePostsController::class);
$route->delete('/posts/{id}', 'posts.delete', ApiController\Posts\DeletePostController::class);

/*
|--------------------------------------------------------------------------
| Question
|--------------------------------------------------------------------------
*/

$route->post('/questions/{question_id}/answer', 'questions.answer.create', ApiController\Question\CreateQuestionAnswerController::class);

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
| Attachment
|--------------------------------------------------------------------------
*/

$route->get('/attachments/{id}', 'attachments.resource', ApiController\Attachment\ResourceAttachmentController::class);
$route->post('/attachments', 'attachments.create', ApiController\Attachment\CreateAttachmentController::class);
$route->delete('/attachments/{id}', 'attachments.delete', ApiController\Attachment\DeleteAttachmentController::class);

/*
 |--------------------------------------------------------------------------
 | Order
 |--------------------------------------------------------------------------
 */

$route->get('/orders/{order_sn}', 'orders.resource', ApiController\Order\ResourceOrderController::class);
$route->post('/orders', 'orders.create', ApiController\Order\CreateOrderController::class);
$route->get('/orders', 'orders.list', ApiController\Order\ListOrdersController::class);

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
| Notification
|--------------------------------------------------------------------------
*/

$route->get('/notification', 'notification.list', ApiController\Notification\ListNotificationController::class);
$route->get('/notification/tpl', 'notification.tpl.list', ApiController\Notification\ListNotificationTplController::class);
$route->get('/notification/tpl/{id}', 'notification.tpl.show', ApiController\Notification\ResourceNotificationTplController::class);
$route->patch('/notification/tpl/{id}', 'notification.tpl.update', ApiController\Notification\UpdateNotificationTplController::class);
$route->get('/notification/{id}', 'notification.resource', ApiController\Notification\ResourceNotificationController::class);
$route->delete('/notification/{id}', 'notification.delete', ApiController\Notification\DeleteNotificationController::class);

/*
|--------------------------------------------------------------------------
| Invite
|--------------------------------------------------------------------------
*/

$route->get('/invite', 'invite.list', ApiController\Invite\ListInviteController::class);
$route->get('/invite/users', 'invite.user.list', ApiController\Invite\ListInviteUsersController::class);
$route->get('/invite/{code}', 'invite.resource', ApiController\Invite\ResourceInviteController::class);
$route->get('/userInviteCode', 'invite.userInviteCode', ApiController\Invite\UserInviteCodeController::class);
$route->post('/invite', 'invite.create.admin', ApiController\Invite\CreateAdminInviteController::class);
$route->delete('/invite/{id}', 'invite.delete', ApiController\Invite\DeleteInviteController::class);

/*
|--------------------------------------------------------------------------
| Emoji
|--------------------------------------------------------------------------
*/

$route->get('/emoji', 'emoji.list', ApiController\Emoji\ListEmojiController::class);

/*
|--------------------------------------------------------------------------
| Statistic
|--------------------------------------------------------------------------
*/

$route->get('/statistic/finance', 'statistic.finance', ApiController\Statistic\FinanceProfileController::class);
$route->get('/statistic/financeChart', 'statistic.financeChart', ApiController\Statistic\FinanceChartController::class);
$route->post('/statistic/miniprogram', 'statistic.miniProgramStat', ApiController\Statistic\MiniProgramStatController::class);

/*
|--------------------------------------------------------------------------
| Follow
|--------------------------------------------------------------------------
*/

$route->post('/follow', 'follow.create', ApiController\Users\CreateUserFollowController::class);
$route->get('/follow', 'follow.list', ApiController\Users\ListUserFollowController::class);
$route->delete('/follow', 'follow.delete', ApiController\Users\DeleteUserFollowController::class);
$route->delete('/follow/{id}/{type}', 'follow.delete.type', ApiController\Users\DeleteUserFollowByTypeController::class);

/*
|--------------------------------------------------------------------------
| Dialog
|--------------------------------------------------------------------------
*/

$route->post('/dialog', 'dialog.create', ApiController\Dialog\CreateDialogController::class);
$route->post('/dialog/batch', 'dialog.batchCreate', ApiController\Dialog\BatchCreateDialogController::class);
$route->get('/dialog', 'dialog.list', ApiController\Dialog\ListDialogController::class);
$route->post('/dialog/message', 'dialog.message.create', ApiController\Dialog\CreateDialogMessageController::class);
$route->get('/dialog/message', 'dialog.message.list', ApiController\Dialog\ListDialogMessageController::class);
$route->delete('/dialog/{id}', 'dialog.delete', ApiController\Dialog\DeleteDialogController::class);

/*
|--------------------------------------------------------------------------
| Reports
|--------------------------------------------------------------------------
*/

$route->post('/reports', 'reports.create', ApiController\Report\CreateReportsController::class);
$route->get('/reports', 'reports.list', ApiController\Report\ListReportsController::class);
$route->patch('/reports/batch', 'reports.batchUpdate', ApiController\Report\BatchUpdateReportsController::class);
$route->delete('/reports/batch/{ids}', 'reports.batchDelete', ApiController\Report\BatchDeleteReportsController::class);

/*
|--------------------------------------------------------------------------
| Analysis
|--------------------------------------------------------------------------
*/

$route->post('/goods/analysis', 'goods.analysis', ApiController\Analysis\ResourceAnalysisGoodsController::class);
$route->get('/goods/{id}', 'goods.resource', ApiController\Analysis\ResourceGoodsController::class);

/*
|--------------------------------------------------------------------------
| Topic
|--------------------------------------------------------------------------
*/

$route->get('/topics', 'topics.list', ApiController\Topic\ListTopicController::class);
$route->get('/topics/{id}', 'topics.resource', ApiController\Topic\ResourceTopicController::class);
$route->delete('/topics/{id}', 'topics.delete', ApiController\Topic\DeleteTopicController::class);
$route->delete('/topics/batch/{ids}', 'topics.batchDelete', ApiController\Topic\BatchDeleteTopicController::class);
$route->patch('/topics/{id}', 'topics.update', ApiController\Topic\UpdateTopicController::class);
$route->patch('/topics/batch/{ids}', 'topics.batchUpdate', ApiController\Topic\BatchUpdateTopicController::class);

/*
|--------------------------------------------------------------------------
| System
|--------------------------------------------------------------------------
*/

$route->get('/system/command', 'system.command', ApiController\System\CallCommandController::class);

/*
|--------------------------------------------------------------------------
| Wechat - Offiaccount
|--------------------------------------------------------------------------
*/

$route->get('/offiaccount/server', 'offiaccount.server', ApiController\Wechat\OffIAccountServerController::class);
$route->post('/offiaccount/server', 'offiaccount.server', ApiController\Wechat\OffIAccountServerController::class);
$route->get('/offiaccount/jssdk', 'offiaccount.jssdk', ApiController\Wechat\OffIAccountJSSDKController::class);
$route->get('/offiaccount/asset', 'offiaccount.asset.list', ApiController\Wechat\OffIAccountAssetListController::class);
$route->get('/offiaccount/asset/{media_id}', 'offiaccount.asset.resource', ApiController\Wechat\OffIAccountAssetResourceController::class);
$route->post('/offiaccount/asset', 'offiaccount.asset.upload', ApiController\Wechat\OffIAccountAssetUploadController::class);
$route->patch('/offiaccount/asset', 'offiaccount.asset.update', ApiController\Wechat\OffIAccountAssetUpdateController::class);
$route->delete('/offiaccount/asset/{media_id}', 'offiaccount.asset.delete', ApiController\Wechat\OffIAccountAssetDeleteController::class);
$route->get('/offiaccount/reply', 'offiaccount.reply.list', ApiController\Wechat\OffIAccountReplyListController::class);
$route->post('/offiaccount/reply', 'offiaccount.reply.create', ApiController\Wechat\OffIAccountReplyCreateController::class);
$route->get('/offiaccount/reply/{id}', 'offiaccount.reply.resource', ApiController\Wechat\OffIAccountReplyResourceController::class);
$route->delete('/offiaccount/reply/{id}', 'offiaccount.reply.delete', ApiController\Wechat\OffIAccountReplyDeleteController::class);
$route->get('/offiaccount/menu', 'offiaccount.menu.list', ApiController\Wechat\OffIAccountMenuListController::class);
$route->post('/offiaccount/menu', 'offiaccount.menu.batchCreate', ApiController\Wechat\OffIAccountMenuBatchCreateController::class);
$route->get('/offiaccount/reprint/{id}', 'offiaccount.threads.reprint', ApiController\Wechat\OffIAccountThreadsReprintController::class);
$route->get('/offiaccount/transform', 'offiaccount.threads.transform', ApiController\Wechat\OffIAccountThreadsTransformController::class);
