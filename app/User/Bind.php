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
use App\Models\User;
use App\Models\UserUcenter;
use App\Models\UserWechat;
use App\Repositories\MobileCodeRepository;
use App\Settings\SettingsRepository;
use Discuz\Foundation\Application;
use Discuz\Socialite\Exception\SocialiteException;
use Discuz\Wechat\EasyWechatTrait;
use EasyWeChat\Kernel\Exceptions\DecryptException;
use EasyWeChat\Kernel\Exceptions\InvalidConfigException;
use Exception;
use Illuminate\Support\Arr;

class Bind
{
    use EasyWechatTrait;

    protected $app;

    protected $mobileCode;

    protected $settings;

    protected $platform = [
        'wechat' => 'mp_openid',
        'wechatweb' => 'dev_openid',
    ];

    public function __construct(Application $app, MobileCodeRepository $mobileCode, SettingsRepository $settings)
    {
        $this->app = $app;
        $this->mobileCode = $mobileCode;
        $this->settings = $settings;
    }

    /**
     * @param $token
     * @param $user
     * @param int $rebind 是否更换绑定
     * @throws Exception
     */
    public function withToken($token, $user, $rebind)
    {
        $session = SessionToken::get($token);
        $scope = Arr::get($session, 'scope');
        if (in_array($scope, ['wechat', 'wechatweb'])) {
            $openid = Arr::get($session, 'payload.openid');
            $wechatUser = UserWechat::where('user_id', $user->id)->first();
            if (!$wechatUser) {
                $wechat = UserWechat::where($this->platform[$scope], $openid)->first();
            }

            // 换绑时删除原双向关系（登陆用户绑定微信，微信绑定的用户）
            if ($rebind) {
                if ($wechatUser) {
                    $wechatUser->user_id = null;
                    $wechatUser->save();
                }
                if ($wechat) {
                    $wechat->user_id = null;
                    $wechat->save();
                }
            }

            // 已经存在绑定，抛出异常
            if ($wechatUser || !$wechat || $wechat->user_id) {
                throw new Exception('account_has_been_bound');
            }

            $wechat->user_id = $user->id;
            /**
             * 如果用户没有头像，绑定微信时观察者中设置绑定微信用户头像
             * @see UserWechatObserver
             */
            $wechat->save();
        }
        if ($scope === 'ucenter') {
            $payload = Arr::get($session, 'payload');
            $user_ucenter = UserUcenter::where('user_id', $user->id)->first();
            if (!is_null($user_ucenter)) {
                throw new Exception('account_has_been_bound');
            }
            $user_ucenter = new UserUcenter();
            $user_ucenter->user_id = $user->id;
            $user_ucenter->ucenter_id = $payload[0];
            $user_ucenter->save();
        }
    }

    /**
     * 绑定微信小程序
     * @param $js_code
     * @param $iv
     * @param $encryptedData
     * @param int $rebind 更换绑定关系
     * @param $user
     * @param bool $isMiniProgramLogin 小程序登陆时调取时传true，可以正常更新userwechat数据并返回供绑定用户无感登陆使用
     * @return UserWechat
     * @throws DecryptException
     * @throws InvalidConfigException
     * @throws SocialiteException
     * @throws Exception
     */
    public function bindMiniprogram($js_code, $iv, $encryptedData, $rebind, $user, $isMiniProgramLogin = false)
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

        // 换绑时删除原双向关系（微信绑定的用户.登陆用户绑定微信）
        if ($rebind) {
            $wechatUser && $wechatUser->delete();
            $user->wechat && $user->wechat->delete();
        } else {
            // 非无感模式 且 非小程序抽屉授权登陆时，用户、微信已经存在绑定关系，抛出异常
            if ($this->settings->get('register_type') != 2 && (!$isMiniProgramLogin || !$user->isGuest())) {
                if (
                    (!is_null($user->wechat) && $user->wechat->min_openid) ||
                    ($wechatUser && $wechatUser->user_id && $user->id != $wechatUser->user_id) ||
                    ($wechatUser && $wechatUser->user_id && $wechatUser->min_openid)
                ) {
                    throw new Exception('account_has_been_bound');
                }
            }
        }

        if (!$wechatUser || !$wechatUser->exists) {
            $wechatUser = UserWechat::build([]);
        }

        //解密获取数据，更新/插入wechatUser
        if (!$wechatUser->user_id) {
            //注册并绑定、登陆并绑定、手机号登陆注册并绑定时设置关联关系
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

    /**
     * 绑定手机号
     * @param $mobileToken
     * @param $user
     * @throws Exception
     */
    public function mobile($mobileToken, $user)
    {
        $session = SessionToken::get($mobileToken);
        if ($session && $session->payload[0]) {
            $usedCount = User::query()->where('mobile', $session->payload[0])->count();
            if ($usedCount) {
                throw new Exception('mobile_is_already_bind');
            }
            if (!$user->mobile) {
                $user->changeMobile($session->payload[0]);
                $user->save();
            } else {
                throw new Exception('user_has_mobile');
            }
        }
    }
}
