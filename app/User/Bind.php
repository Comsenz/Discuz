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
use Discuz\Socialite\Exception\SocialiteException;
use Discuz\Wechat\EasyWechatTrait;
use Illuminate\Support\Arr;

class Bind
{
    use EasyWechatTrait;

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

    /**
     * 绑定微信公众号
     * @param $token
     * @param $user
     * @throws \Exception
     */
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

    /**
     * 绑定微信小程序
     * @param $js_code
     * @param $iv
     * @param $encryptedData
     * @param $user
     * @throws SocialiteException
     */
    public function bindMiniprogram($js_code, $iv, $encryptedData, $user)
    {
        $app = $this->miniProgram();
        //获取小程序登陆session key
        $authSession = $app->auth->session($js_code);
        if (isset($authSession['errcode']) && $authSession['errcode'] != 0) {
            throw new SocialiteException($authSession['errmsg'], $authSession['errcode']);
        }
        $decryptedData = $app->encryptor->decryptData(Arr::get($authSession, 'session_key'), $iv, $encryptedData);
        $unionid = Arr::get($decryptedData, 'unionId') ?: Arr::get($authSession, 'unionid', '');
        $openid  =  Arr::get($decryptedData, 'openId') ?: Arr::get($authSession, 'openid');

        //获取小程序用户信息
        /** @var UserWechat $wechatUser */
        $wechatUser = UserWechat::when($unionid, function ($query, $unionid) {
            return $query->where('unionid', $unionid);
        })->orWhere('min_openid', $openid)->first();

        if (!$wechatUser) {
            $wechatUser = UserWechat::build([]);
        }

        //解密获取数据，更新/插入wechatUser
        if (!$wechatUser->user_id) {
            $wechatUser->user_id = $user->id ?: null;
        }
        $wechatUser->unionid = $unionid;
        $wechatUser->min_openid = $openid;
        $wechatUser->nickname = $decryptedData['nickName'];
        $wechatUser->city = $decryptedData['city'];
        $wechatUser->province = $decryptedData['province'];
        $wechatUser->country = $decryptedData['country'];
        $wechatUser->sex = $decryptedData['gender'];
        $wechatUser->headimgurl = $decryptedData['avatarUrl'];
        $wechatUser->save();

        return $wechatUser;
    }
}
