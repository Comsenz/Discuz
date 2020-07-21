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

namespace App\User;

use App\Models\SessionToken;
use App\Models\UserWechat;
use App\Repositories\MobileCodeRepository;
use Discuz\Foundation\Application;
use Illuminate\Support\Arr;

class Bind
{
    protected $app;

    protected $mobileCode;

    protected $platform = [
        'wechat' => 'mp_openid',
        'wechatweb' => 'dev_openid',
    ];

    public function __construct(Application $app, MobileCodeRepository $mobileCode)
    {
        $this->app = $app;
        $this->mobileCode = $mobileCode;
    }

    public function wechat($token, $user)
    {
        $session = SessionToken::get($token);
        $scope = Arr::get($session, 'scope');
        $openid = Arr::get($session, 'payload.openid');
        if (in_array($scope, ['wechat', 'wechatweb'])) {
            $wechatUser = UserWechat::where('user_id', $user->id)->first();
            if (!$wechatUser) {
                $wechat = UserWechat::where($this->platform[$scope], $openid)->first();
            }
            // 已经存在绑定，抛出异常
            if ($wechatUser || !$wechat || $wechat->user_id) {
                throw new \Exception('account_has_been_bound');
            }

            $wechat->user_id = $user->id;
            /**
             * 如果用户没有头像，绑定微信时观察者中设置绑定微信用户头像
             * @see UserWechatObserver
             */
            $wechat->save();
        }
    }
}
