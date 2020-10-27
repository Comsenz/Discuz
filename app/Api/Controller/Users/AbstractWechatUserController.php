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
use App\Api\Serializer\UserProfileSerializer;
use App\Commands\Users\AutoRegisterUser;
use App\Commands\Users\GenJwtToken;
use App\Events\Users\Logind;
use App\Exceptions\NoUserException;
use App\MessageTemplate\Wechat\WechatRegisterMessage;
use App\Models\SessionToken;
use App\Models\User;
use App\Models\UserWechat;
use App\Notifications\System;
use App\Settings\SettingsRepository;
use App\User\Bound;
use Discuz\Api\Controller\AbstractResourceController;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Auth\Exception\PermissionDeniedException;
use Discuz\Auth\Guest;
use Discuz\Contracts\Socialite\Factory;
use Exception;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Contracts\Events\Dispatcher as Events;
use Illuminate\Contracts\Validation\Factory as ValidationFactory;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

abstract class AbstractWechatUserController extends AbstractResourceController
{
    use AssertPermissionTrait;

    protected $socialite;

    protected $bus;

    protected $cache;

    protected $validation;

    protected $events;

    protected $settings;

    protected $bound;

    public $serializer = TokenSerializer::class;

    public function __construct(Factory $socialite, Dispatcher $bus, Repository $cache, ValidationFactory $validation, Events $events, SettingsRepository $settings, Bound $bound)
    {
        $this->socialite = $socialite;
        $this->bus = $bus;
        $this->cache = $cache;
        $this->validation = $validation;
        $this->events = $events;
        $this->settings = $settings;
        $this->bound = $bound;
    }

    /**
     * @param ServerRequestInterface $request
     * @param Document $document
     * @return mixed
     * @throws NoUserException
     * @throws PermissionDeniedException
     * @throws Exception
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $sessionId = Arr::get($request->getQueryParams(), 'sessionId');

        $sessionToken = Arr::get($request->getQueryParams(), 'session_token', null);

        $request = $request->withAttribute('session', new SessionToken())->withAttribute('sessionId', $sessionId);

        $this->validation->make([
            'code' => Arr::get($request->getQueryParams(), 'code'),
            'sessionId' => Arr::get($request->getQueryParams(), 'sessionId'),
        ], [
            'code' => 'required',
            'sessionId' => 'required'
        ])->validate();

        $this->socialite->setRequest($request);

        $driver = $this->socialite->driver($this->getDriver());

        $wxuser = $driver->user();

        /** @var User $actor */
        $actor = $request->getAttribute('actor');

        /** @var UserWechat $wechatUser */
        $wechatUser = UserWechat::where($this->getType(), $wxuser->getId())->orWhere('unionid', Arr::get($wxuser->getRaw(), 'unionid'))->first();

        // 换绑时直接返回token供后续操作使用
        if ($rebind = Arr::get($request->getQueryParams(), 'rebind', 0)) {
            $this->error($wxuser, new Guest(), $wechatUser, $rebind, $sessionToken);
        }

        if (!$wechatUser || !$wechatUser->user) {
            // 更新微信用户信息
            if (!$wechatUser) {
                $wechatUser = new UserWechat();
            }
            $wechatUser->setRawAttributes($this->fixData($wxuser->getRaw(), $actor));

            // 自动注册
            if (Arr::get($request->getQueryParams(), 'register', 0) && $actor->isGuest()) {
                // 站点关闭注册
                if (!(bool)$this->settings->get('register_close')) {
                    throw new PermissionDeniedException('register_close');
                }

                $data['code'] = Arr::get($request->getQueryParams(), 'inviteCode');
                $data['username'] = Str::of($wechatUser->nickname)->substr(0, 15);
                $data['register_reason'] = trans('user.register_by_wechat_h5');
                $user = $this->bus->dispatch(
                    new AutoRegisterUser($request->getAttribute('actor'), $data)
                );
                $wechatUser->user_id = $user->id;
                // 先设置关系，为了同步微信头像
                $wechatUser->setRelation('user', $user);
                $wechatUser->save();

                // 判断是否开启了注册审核
                if (!(bool)$this->settings->get('register_validate')) {
                    // 在注册绑定微信后 发送注册微信通知
                    $user->notify(new System(WechatRegisterMessage::class));
                }
            } else {
                if (!$actor->isGuest() && is_null($actor->wechat)) {
                    // 登陆用户且没有绑定||换绑微信 添加微信绑定关系
                    $wechatUser->user_id = $actor->id;
                    $wechatUser->setRelation('user', $actor);
                    $wechatUser->save();
                }
            }
        } else {
            // 登陆用户和微信绑定不同时，微信已绑定用户，抛出异常
            if (!$actor->isGuest() && $actor->id != $wechatUser->user_id) {
                throw new Exception('account_has_been_bound');
            }

            // 登陆用户和微信绑定相同，更新微信信息
            $wechatUser->setRawAttributes($this->fixData($wxuser->getRaw(), $wechatUser->user));
            $wechatUser->save();
        }

        if ($wechatUser && $wechatUser->user) {
            // 创建 token
            $params = [
                'username' => $wechatUser->user->username,
                'password' => ''
            ];

            $data = $this->fixData($wxuser->getRaw(), $actor);
            unset($data['user_id']);
            $wechatUser->setRawAttributes($data);
            $wechatUser->save();

            $response = $this->bus->dispatch(
                new GenJwtToken($params)
            );

            if ($response->getStatusCode() === 200) {
                $this->events->dispatch(new Logind($wechatUser->user));
            }

            $accessToken = json_decode($response->getBody());

            // bound
            if (Arr::has($request->getQueryParams(), 'session_token')) {
                $accessToken = $this->bound->pcLogin($sessionToken, $accessToken, ['user_id' => $wechatUser->user->id]);
            }

            return $accessToken;
        }

        $this->error($wxuser, $actor, $wechatUser, null, $sessionToken);
    }

    /**
     * @param $wxuser
     * @param $actor
     * @param UserWechat $wechatUser
     * @param null $rebind 换绑时返回新的code供前端使用
     * @param null $sessionToken
     * @return mixed
     * @throws NoUserException
     */
    private function error($wxuser, $actor, $wechatUser, $rebind = null, $sessionToken = null)
    {
        $rawUser = $wxuser->getRaw();

        if (!$wechatUser) {
            $wechatUser = new UserWechat();
        }
        $wechatUser->setRawAttributes($this->fixData($rawUser, $actor));
        $wechatUser->save();

        if ($actor->id) {
            $this->serializer = UserProfileSerializer::class;
            return $actor;
        }

        $token = SessionToken::generate($this->getDriver(), $rawUser);
        $token->save();

        $noUserException = new NoUserException();
        $noUserException->setToken($token);
        $noUserException->setUser(Arr::only($wechatUser->toArray(), ['nickname', 'headimgurl']));
        $rebind && $noUserException->setCode('rebind_mp_wechat');

        // 存储异常 PC 端使用
        if (!is_null($sessionToken)) {
            $sessionTokenQuery = SessionToken::query()->where('token', $sessionToken)->first();
            if (!empty($sessionTokenQuery)) {
                /** @var SessionToken $sessionTokenQuery */
                $sessionTokenQuery->payload = [
                    'token' => $token,
                    'code' => $noUserException->getCode() ?: 'no_bind_user',
                    'user' => $noUserException->getUser(),
                    'rebind' => $rebind,
                ];
                $sessionTokenQuery->save();
            }
        }

        throw $noUserException;
    }

    abstract protected function getDriver();

    abstract protected function getType();

    protected function fixData($rawUser, $actor)
    {
        $data = array_merge($rawUser, ['user_id' => $actor->id ?: null, $this->getType() => $rawUser['openid']]);
        unset($data['openid'], $data['language']);
        $data['privilege'] = serialize($data['privilege']);
        return $data;
    }
}
