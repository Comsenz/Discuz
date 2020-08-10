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

use App\Models\User;
use App\Repositories\UserRepository;
use Discuz\Socialite\Exception\SocialiteException;
use Discuz\Wechat\EasyWechatTrait;
use EasyWeChat\Kernel\Exceptions\DecryptException;
use EasyWeChat\Kernel\Exceptions\InvalidConfigException;
use Illuminate\Support\Arr;

class BindWchatMiniprogramMobile
{
    use EasyWechatTrait;

    public $app;

    protected $data;

    protected $actor;

    public function __construct(array $data, User $actor)
    {
        $this->actor = $actor;
        $this->data = $data;
    }

    /**
     * @return User
     * @throws SocialiteException
     * @throws DecryptException
     * @throws InvalidConfigException
     */
    public function handle(UserRepository $users)
    {
        $app = $this->miniProgram();
        //获取小程序登陆session key
        $authSession = $app->auth->session(Arr::get($this->data, 'js_code'));
        if (isset($authSession['errcode']) && $authSession['errcode'] != 0) {
            throw new SocialiteException($authSession['errmsg'], $authSession['errcode']);
        }
        $decryptedData = $app->encryptor->decryptData(
            Arr::get($authSession, 'session_key'),
            Arr::get($this->data, 'iv'),
            Arr::get($this->data, 'encryptedData')
        );
        $mobile = Arr::get($decryptedData, 'purePhoneNumber', '');
        if ($mobile) {
            $usedCount = $users->query()->where('mobile', $mobile)->count();
            if ($usedCount) {
                throw new \Exception('mobile_is_already_bind');
            }
            $this->actor->changeMobile($mobile);
            $this->actor->save();
            return $this->actor;
        } else {
            throw new \Exception('wechat_mobile_unbound');
        }
    }
}
