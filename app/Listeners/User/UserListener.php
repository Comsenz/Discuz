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

namespace App\Listeners\User;

use App\Events\Users\ChangeUserStatus;
use App\Events\Users\PayPasswordChanged;
use App\Events\Users\UserFollowCreated;
use App\Models\SessionToken;
use App\Notifications\Messages\Database\StatusMessage;
use App\Notifications\System;
use Illuminate\Contracts\Events\Dispatcher;

class UserListener
{
    public function subscribe(Dispatcher $events)
    {
        // 刷新用户关注数粉丝数
        $events->listen(UserFollowCreated::class, [$this, 'refreshFollowCount']);

        // 通知
        $events->listen(ChangeUserStatus::class, [$this, 'notifications']);

        // 修改支付密码
        $events->listen(PayPasswordChanged::class, [$this, 'payPasswordChanged']);
    }

    public function refreshFollowCount(UserFollowCreated $event)
    {
        //关注人的 关注数
        $event->fromUser->refreshUserFollow();
        $event->fromUser->save();

        //被关注人的 粉丝数
        $event->toUser->refreshUserFans();
        $event->toUser->save();
    }

    /**
     * @param ChangeUserStatus $event
     */
    public function notifications(ChangeUserStatus $event)
    {
        $user = $event->user;

        // Tag 发送通知
        $user->notify(new System(StatusMessage::class, $user, ['refuse' => $event->refuse]));
    }

    public function payPasswordChanged(PayPasswordChanged $event)
    {
        // 修改支付密码后，清除用于修改支付密码的 session_token
        SessionToken::query()
            ->where('scope', 'reset_pay_password')
            ->where('user_id', $event->user->id)
            ->delete();
    }
}
