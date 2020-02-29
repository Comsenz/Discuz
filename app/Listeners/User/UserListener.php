<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Listeners\User;

use App\Events\Users\ChangeUserStatus;
use App\Events\Users\PayPasswordChanged;
use App\Events\Users\UserFollowCount;
use App\Events\Users\UserRefreshCount;
use App\MessageTemplate\StatusMessage;
use App\Models\SessionToken;
use App\Notifications\System;
use Illuminate\Contracts\Events\Dispatcher;

class UserListener
{
    public function subscribe(Dispatcher $events)
    {
        // 刷新分类数
        $events->listen(UserRefreshCount::class, [$this, 'refreshCount']);

        // 刷新用户关注数粉丝数
        $events->listen(UserFollowCount::class, [$this, 'refreshFollowCount']);

        // 通知
        $events->listen(ChangeUserStatus::class, [$this, 'notifications']);

        // 修改支付密码
        $events->listen(PayPasswordChanged::class, [$this, 'payPasswordChanged']);
    }

    public function refreshCount(UserRefreshCount $event)
    {
        $event->user->refreshThreadCount();

        $event->user->save();
    }

    public function refreshFollowCount(UserFollowCount $event)
    {
        //关注人的 关注数
        $event->fromUser->refreshUserFollow();
        $event->fromUser->save();

        //被关注人的 粉丝数
        $event->toUser->refreshUserFans();
        $event->toUser->save();
    }

    public function notifications(ChangeUserStatus $event)
    {
        $user = $event->user;

        $user->notify(new System(StatusMessage::class, ['refuse' => $event->refuse]));
    }

    public function payPasswordChanged(PayPasswordChanged $event)
    {
        // 修改支付密码后，清除用于修改支付密码的 session_token
        SessionToken::where('scope', 'reset_pay_password')
            ->where('user_id', $event->user->id)
            ->delete();
    }
}
