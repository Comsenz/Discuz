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
use App\Commands\Users\GenJwtToken;
use App\Commands\Users\RegisterWechatQyUser;
use App\Events\Users\Logind;
use App\Exceptions\NoUserException;
use App\Models\SessionToken;
use App\Models\UserQyWechats;
use App\Models\UserWechat;
use Discuz\Api\Controller\AbstractResourceController;
use Discuz\Contracts\Socialite\Factory;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Contracts\Events\Dispatcher as Events;
use Illuminate\Contracts\Validation\Factory as ValidationFactory;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

abstract class AbstractWechatQyUserController extends AbstractResourceController
{
    public $serializer = TokenSerializer::class;

    protected $socialite;

    protected $bus;

    protected $cache;

    protected $validation;

    protected $events;

    public function __construct(Factory $socialite, Dispatcher $bus, Repository $cache, ValidationFactory $validation, Events $events)
    {
        $this->socialite = $socialite;
        $this->bus = $bus;
        $this->cache = $cache;
        $this->validation = $validation;
        $this->events = $events;
    }

    protected function data(ServerRequestInterface $request, Document $document)
    {
        $sessionId = Arr::get($request->getQueryParams(), 'sessionId');

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
        $user = $driver->user();
        $wechatUser = UserQyWechats::where($this->getType(), $user->getId())->first();
        if (! $wechatUser || ! $wechatUser->user) {
            //注册
            if (!$wechatUser) {
                $preData['qy_userid'] = $user->id;
                $preData['nickname'] = $user->nickname;
                $preData['sex'] = $user->sex;
                $preData['headimgurl'] = $user->avatar;
                $preData['mobile'] = $user->user['mobile'];
                $preData['address'] = $user->user['address'];
                $preData['email'] = $user->user['email'];

                $wechatUser = UserQyWechats::build(Arr::Only(
                    $preData,
                    ['qy_userid', 'nickname', 'sex', 'headimgurl', 'email', 'address', 'mobile']
                ));
                $wechatUser->save();
            }
            $data['username'] = Str::of($wechatUser->nickname)->substr(0, 15);
            $data['register_ip'] = ip($request->getServerParams());
            $data['register_port'] = Arr::get($request->getServerParams(), 'REMOTE_PORT', 0);
            $registerWechatQyUser = $this->bus->dispatch(
                new RegisterWechatQyUser($request->getAttribute('actor'), $data)
            );
            $wechatUser->user_id = $registerWechatQyUser->id;
            $wechatUser->save();
        }
        //创建 token
        $params = [
            'username' => $user->nickname,
            'password' => ''
        ];
        $response = $this->bus->dispatch(
            new GenJwtToken($params)
        );
        if ($response->getStatusCode() === 200) {
            $this->events->dispatch(new Logind($wechatUser->user));
        }
        return json_decode($response->getBody());
    }

    /**
     * @param $user
     * @param $actor
     * @param UserWechat $wechatUser
     * @return mixed
     * @throws NoUserException
     */
    private function error($user, $actor, $wechatUser)
    {
        $rawUser = $user->getRaw();

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
        throw (new NoUserException())->setToken($token);
    }

    abstract protected function getDriver();

    abstract protected function getType();

    protected function fixData($rawUser, $actor)
    {
        $data = array_merge($rawUser, ['user_id' => $actor->id, $this->getType() => $rawUser['']]);
        return $data;
    }
}
