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

namespace App\Api\Controller\Users;

use App\Api\Serializer\TokenSerializer;
use App\Commands\Users\AutoRegisterUser;
use App\Commands\Users\GenJwtToken;
use App\Events\Users\Logind;
use App\Exceptions\NoUserException;
use App\Settings\SettingsRepository;
use App\User\Bind;
use Discuz\Api\Controller\AbstractResourceController;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Auth\Exception\PermissionDeniedException;
use Discuz\Auth\Guest;
use Discuz\Socialite\Exception\SocialiteException;
use Discuz\Wechat\EasyWechatTrait;
use Exception;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Contracts\Events\Dispatcher as Events;
use Illuminate\Contracts\Validation\Factory as ValidationFactory;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class WechatMiniProgramLoginController extends AbstractResourceController
{
    use AssertPermissionTrait;
    use EasyWechatTrait;

    public $serializer = TokenSerializer::class;

    protected $bus;

    protected $cache;

    protected $validation;

    protected $events;

    protected $settings;

    protected $bind;

    public function __construct(Dispatcher $bus, Repository $cache, ValidationFactory $validation, Events $events, SettingsRepository $settings, Bind $bind)
    {
        $this->bus = $bus;
        $this->cache = $cache;
        $this->validation = $validation;
        $this->events = $events;
        $this->settings = $settings;
        $this->bind = $bind;
    }

    /**
     * @inheritDoc
     * @throws SocialiteException
     * @throws PermissionDeniedException
     * @throws Exception
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $attributes = Arr::get($request->getParsedBody(), 'data.attributes', []);
        $js_code = Arr::get($attributes, 'js_code');
        $iv = Arr::get($attributes, 'iv');
        $encryptedData =Arr::get($attributes, 'encryptedData');
        $this->validation->make(
            $attributes,
            ['js_code' => 'required','iv' => 'required','encryptedData' => 'required']
        )->validate();

        $actor = $request->getAttribute('actor');
        $user = !$actor->isGuest() ? $actor : new Guest();

        // 绑定小程序
        $rebind = Arr::get($attributes, 'rebind', 0);
        $wechatUser = $this->bind->bindMiniprogram($js_code, $iv, $encryptedData, $rebind, $user, true);

        if ($wechatUser->user_id) {
            //已绑定的用户登陆
            $user = $wechatUser->user;

            //用户被删除
            if (!$user) {
                throw new \Exception('bind_error');
            }
        } else {
            //自动注册
            if (Arr::get($attributes, 'register', 0)) {
                //未绑定的用户注册
                if (!(bool)$this->settings->get('register_close')) {
                    throw new PermissionDeniedException('register_close');
                }

                //注册邀请码
                $data['code'] = Arr::get($attributes, 'code');
                $data['username'] = Str::of($wechatUser->nickname)->substr(0, 15);
                $data['register_reason'] = trans('user.register_by_wechat_miniprogram');
                $user = $this->bus->dispatch(
                    new AutoRegisterUser($request->getAttribute('actor'), $data)
                );
                $wechatUser->user_id = $user->id;
                // 先设置关系再save，为了同步微信头像
                $wechatUser->setRelation('user', $user);
                $wechatUser->save();
            } else {
                $noUserException = new NoUserException();
                $noUserException->setUser(['username' => $wechatUser->nickname, 'headimgurl'=>$wechatUser->headimgurl]);
                throw $noUserException;
            }
        }

        //创建 token
        $params = [
            'username' => $user->username,
            'password' => ''
        ];

        $response = $this->bus->dispatch(
            new GenJwtToken($params)
        );

        if ($response->getStatusCode() === 200) {
            $this->events->dispatch(new Logind($user));
        }

        return json_decode($response->getBody());
    }
}
