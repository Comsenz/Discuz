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

namespace App\Commands\Users;

use App\Models\SessionToken;
use App\Models\UserWechat;
use EasyWeChat\Kernel\Messages\Text;
use Illuminate\Support\Arr;

class WebUserEvent
{
    protected $app;

    public function __construct($app)
    {
        $this->app = $app;
    }

    public function handle()
    {
        $this->app->server->push(function ($message) {
            switch ($message['Event']) {
                case 'subscribe':
                case 'SCAN':
                    return $this->event($message);
                    break;
            }
        });
    }

    /**
     * @param $message
     * @return Text
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    protected function event($message)
    {
        $openid = $message['FromUserName'];
        $EventKey = str_replace('qrscene_', '', $message['EventKey']);
        $wechatuser = UserWechat::where('mp_openid', $openid)->first();

        if ($wechatuser && $wechatuser->user_id) {
            //老用户  跟新扫描二维码用户
            SessionToken::get($EventKey, 'wechat')->update([
                    'user_id'=>$wechatuser->user_id,
                ]);
            return new Text(trans('login.WebUser_login_success'));
        }

        if (is_null($wechatuser)) {
            $wechatuser = new UserWechat();
        }

        //新用户,跳转绑定页面
        $user = $this->app->user->get($openid);

        $wechatuser->mp_openid = Arr::get($user, 'openid');
        $wechatuser->nickname =  Arr::get($user, 'nickname');
        $wechatuser->sex = Arr::get($user, 'sex');
        $wechatuser->province = Arr::get($user, 'province');
        $wechatuser->city = Arr::get($user, 'city');
        $wechatuser->country = Arr::get($user, 'country');
        $wechatuser->headimgurl = Arr::get($user, 'headimgurl');
        $wechatuser->unionid = Arr::get($user, 'unionid');

        SessionToken::get($EventKey, 'wechat')->update([
            'scope'=>'wechat',
            'payload'=> $user
        ]);

        $wechatuser->save();

        return new Text(trans('login.WebNewUser_login_success'));
    }
}
