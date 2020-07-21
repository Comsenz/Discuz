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
use App\Events\Users\Logind;
use App\Exceptions\NoUserException;
use App\Models\SessionToken;
use App\Models\UserWechat;
use Discuz\Api\Controller\AbstractResourceController;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;
use Discuz\Contracts\Socialite\Factory;
use Illuminate\Contracts\Validation\Factory as ValidationFactory;
use Illuminate\Contracts\Events\Dispatcher as Events;

abstract class AbstractWechatUserController extends AbstractResourceController
{
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

    public $serializer = TokenSerializer::class;

    /**
     * @param ServerRequestInterface $request
     * @param Document $document
     * @return mixed
     * @throws NoUserException
     */
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

        $actor = $request->getAttribute('actor');

        $wechatUser = UserWechat::where($this->getType(), $user->getId())->orWhere('unionid', Arr::get($user->getRaw(), 'unionid'))->first();

        if ($wechatUser && $wechatUser->user) {
            //创建 token
            $params = [
                'username' => $wechatUser->user->username,
                'password' => ''
            ];

            $data = $this->fixData($user->getRaw(), $actor);
            unset($data['user_id']);
            $wechatUser->setRawAttributes($data);
            $wechatUser->save();

            $response = $this->bus->dispatch(
                new GenJwtToken($params)
            );

            if ($response->getStatusCode() === 200) {
                $this->events->dispatch(new Logind($wechatUser->user));
            }

            return json_decode($response->getBody());
        }

        $this->error($user, $actor, $wechatUser);
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

        $noUserException = new NoUserException();
        $noUserException->setToken($token);
        $noUserException->setUser(Arr::only($wechatUser->toArray(), ['nickname', 'headimgurl']));
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
