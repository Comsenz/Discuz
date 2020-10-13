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

namespace App\Commands\Sms;

use App\Api\Controller\Mobile\VerifyController;
use App\Api\Serializer\TokenSerializer;
use App\Api\Serializer\UserSerializer;
use App\Commands\Users\GenJwtToken;
use App\Commands\Users\RegisterPhoneUser;
use App\Events\Users\Logind;
use App\Exceptions\NoUserException;
use App\Exceptions\TranslatorException;
use App\MessageTemplate\Wechat\WechatRegisterMessage;
use App\Models\MobileCode;
use App\Models\SessionToken;
use App\Models\User;
use App\Models\UserWalletFailLogs;
use App\Notifications\System;
use App\Repositories\MobileCodeRepository;
use App\User\Bind;
use App\Validators\UserValidator;
use Discuz\Api\Client;
use Discuz\Auth\Exception\PermissionDeniedException;
use Discuz\Contracts\Setting\SettingsRepository;
use Discuz\Foundation\EventsDispatchTrait;
use Exception;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Contracts\Events\Dispatcher as Events;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class VerifyMobile
{
    use EventsDispatchTrait;

    protected $controller;

    protected $mobileCode;

    protected $apiClient;

    protected $actor;

    protected $bus;

    protected $params;

    protected $validator;

    protected $mobileCodeRepository;

    protected $events;

    protected $bind;

    protected $settings;

    public function __construct(VerifyController $controller, MobileCode $mobileCode, User $actor, $params = [])
    {
        $this->controller = $controller;
        $this->mobileCode = $mobileCode;
        $this->actor = $actor;
        $this->params = $params;
    }

    public function handle(Client $apiClient, Dispatcher $bus, UserValidator $validator, MobileCodeRepository $mobileCodeRepository, Events $events, SettingsRepository $settings, Bind $bind)
    {
        $this->apiClient = $apiClient;
        $this->bus = $bus;
        $this->validator = $validator;
        $this->mobileCodeRepository = $mobileCodeRepository;
        $this->events = $events;
        $this->bind = $bind;
        $this->settings = $settings;
        return call_user_func([$this, Str::camel($this->mobileCode->type)]);
    }

    /**
     * @return mixed
     * @throws PermissionDeniedException
     * @throws NoUserException
     */
    protected function login()
    {
        //register new user
        if (is_null($this->mobileCode->user) && Arr::get($this->params, 'register', 0)) {
            if (!(bool)$this->settings->get('register_close')) {
                throw new PermissionDeniedException('register_close');
            }

            $data['register_ip'] = Arr::get($this->params, 'ip');
            $data['register_port'] = Arr::get($this->params, 'port');
            $data['mobile'] = $this->mobileCode->mobile;
            $data['code'] = Arr::get($this->params, 'inviteCode');
            $user = $this->bus->dispatch(
                new RegisterPhoneUser($this->actor, $data)
            );
            $this->mobileCode->setRelation('user', $user);
        } elseif (is_null($this->mobileCode->user) && !Arr::get($this->params, 'register', 0)) {
            //没用户且不需要自动注册 抛出异常
            $token = SessionToken::generate('mobile', [$this->mobileCode->mobile], null, 3600);
            $token->save();

            $noUserException = new NoUserException();
            $noUserException->setToken($token);
            throw $noUserException;
        }

        //公众号绑定
        if ($token = Arr::get($this->params, 'token')) {
            $this->bind->withToken($token, $this->mobileCode->user);
            if (!(bool)$this->settings->get('register_validate')) {
                // 在注册绑定微信后 发送注册微信通知
                $this->mobileCode->user->notify(new System(WechatRegisterMessage::class));
            }
        }

        //小程序绑定
        $js_code = Arr::get($this->params, 'js_code');
        $iv = Arr::get($this->params, 'iv');
        $encryptedData = Arr::get($this->params, 'encryptedData');
        if ($js_code && $iv && $encryptedData) {
            $this->bind->bindMiniprogram($js_code, $iv, $encryptedData, $this->mobileCode->user);
        }


        $this->events->dispatch(
            new Logind($this->mobileCode->user)
        );
        //login
        $this->controller->serializer = TokenSerializer::class;
        $params = [
            'username' => $this->mobileCode->user->username,
            'password' => ''
        ];

        $response = $this->bus->dispatch(
            new GenJwtToken($params)
        );

        return json_decode($response->getBody());
    }

    /**
     * @return User|mixed
     * @throws Exception
     */
    protected function bind()
    {
        $mobile = $this->mobileCode->mobile;

        // 判断手机号是否已经被绑定
        if ($this->actor->mobile) {
            throw new \Exception('mobile_is_already_bind');
        }

        $this->controller->serializer = UserSerializer::class;
        if ($this->actor->exists) {
            $this->actor->changeMobile($mobile);
            $this->actor->save();
            $this->mobileCode->user = $this->actor;
        }
        return $this->mobileCode->user;
    }

    protected function rebind()
    {
        $mobile = $this->mobileCode->mobile;

        $this->controller->serializer = UserSerializer::class;
        if ($this->actor->exists) {
            // 删除验证身份的验证码
            MobileCode::query()->where('mobile', $this->actor->getRawOriginal('mobile'))
                ->where('type', 'verify')
                ->where('state', 1)
                ->where('updated_at', '<', Carbon::now()->addMinutes(10))
                ->delete();

            $this->actor->changeMobile($mobile);
            $this->actor->save();
            $this->mobileCode->user = $this->actor;
        }
        return $this->mobileCode->user;
    }

    /**
     * @return mixed
     * @throws TranslatorException
     */
    protected function resetPwd()
    {
        $this->controller->serializer = UserSerializer::class;
        if ($this->mobileCode->user && isset($this->params['password'])) {
            $this->validator->valid([
                'password' => $this->params['password']
            ]);

            // 验证新密码与原密码不能相同
            if ($this->mobileCode->user->checkPassword($this->params['password'])) {
                throw new TranslatorException('user_update_error', ['cannot_use_the_same_password']);
            }

            $this->mobileCode->user->changePassword($this->params['password']);
            $this->mobileCode->user->save();
        }
        return $this->mobileCode->user;
    }

    /**
     * @return mixed
     * @throws TranslatorException
     */
    protected function resetPayPwd()
    {
        $this->controller->serializer = UserSerializer::class;
        if ($this->mobileCode->user && isset($this->params['pay_password'])) {
            $this->validator->valid([
                'pay_password' => $this->params['pay_password'],
                'pay_password_confirmation' => $this->params['pay_password_confirmation'],
            ]);

            // 验证新密码与原密码不能相同
            if ($this->mobileCode->user->checkWalletPayPassword($this->params['pay_password'])) {
                throw new TranslatorException('user_update_error', ['cannot_use_the_same_password']);
            }

            $this->mobileCode->user->changePayPassword($this->params['pay_password']);
            $this->mobileCode->user->save();

            // 清空支付密码错误次数
            UserWalletFailLogs::deleteAll($this->mobileCode->user->id);
        }
        return $this->mobileCode->user;
    }

    protected function verify()
    {
        $this->controller->serializer = UserSerializer::class;
        return $this->mobileCode->user;
    }
}
