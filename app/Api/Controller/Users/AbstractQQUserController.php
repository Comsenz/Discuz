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
use App\Commands\Users\GenJwtToken;
use App\Commands\Users\RegisterQQUser;
use App\Events\Users\Logind;
use App\Models\SessionToken;
use App\Models\UserQq;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Contracts\Events\Dispatcher as Events;
use Illuminate\Contracts\Validation\Factory as ValidationFactory;
use Illuminate\Support\Arr;
use Discuz\Contracts\Socialite\Factory;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;
use Discuz\Api\Controller\AbstractResourceController;

abstract class AbstractQQUserController extends AbstractResourceController
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

    /**
     * @inheritDoc
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $sessionId = Arr::get($request->getQueryParams(), 'sessionId');
        $accessToken = Arr::get($request->getQueryParams(), 'access_token');

        $request = $request->withAttribute('session', new SessionToken())
            ->withAttribute('sessionId', $sessionId)
            ->withAttribute('access_token', $accessToken);

        $this->validation->make([
            'access_token' => Arr::get($request->getQueryParams(), 'access_token'),
            'sessionId' => Arr::get($request->getQueryParams(), 'sessionId'),
        ], [
            'access_token' => 'required',
            'sessionId' => 'required'
        ])->validate();
        $this->socialite->setRequest($request);
        $driver = $this->socialite->driver($this->getDriver());
        $user = $driver->user();
        $qqUser = UserQq::where('openid', $user->getId())->first();
        if (! $qqUser || ! $qqUser->user) {
            //注册
            if (!$qqUser) {
                $preData['openid'] = $user->id;
                $preData['nickname'] = $user->nickname;
                $preData['sex'] = $user->sex;
                $preData['headimgurl'] = $user->avatar;
                $preData['province'] = $user->user['province'];
                $preData['city'] = $user->user['city'];

                $qqUser = UserQQ::build(Arr::Only(
                    $preData,
                    ['openid', 'nickname', 'sex', 'headimgurl', 'province', 'city']
                ));
                $qqUser->save();
            }
            $data['username'] = $qqUser->nickname;
            $data['register_ip'] = ip($request->getServerParams());
            $registerUser = $this->bus->dispatch(
                new RegisterQQUser($request->getAttribute('actor'), $data)
            );
            $qqUser->user_id = $registerUser->id;
            $qqUser->save();
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
            $this->events->dispatch(new Logind($qqUser->user));
        }
        return json_decode($response->getBody());
    }

    abstract protected function getDriver();
}
