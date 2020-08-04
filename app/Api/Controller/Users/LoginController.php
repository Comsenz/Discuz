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
use App\Events\Users\Logind;
use App\Passport\Repositories\UserRepository;
use App\User\Bind;
use Discuz\Api\Controller\AbstractResourceController;
use Discuz\Foundation\Application;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Contracts\Events\Dispatcher as Events;
use Illuminate\Support\Arr;
use Illuminate\Validation\Factory as Validator;
use Illuminate\Validation\ValidationException;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class LoginController extends AbstractResourceController
{
    public $serializer = TokenSerializer::class;

    protected $users;

    protected $bus;

    protected $app;

    protected $validator;

    protected $events;

    protected $bind;

    public $include = ['users'];

    public function __construct(UserRepository $users, Dispatcher $bus, Application $app, Validator $validator, Events $events, Bind $bind)
    {
        $this->users = $users;
        $this->bus = $bus;
        $this->app = $app;
        $this->validator = $validator;
        $this->events = $events;
        $this->bind = $bind;
    }

    /**
     * @param ServerRequestInterface $request
     * @param Document $document
     * @return array|mixed
     * @throws ValidationException
     * @throws \Discuz\Socialite\Exception\SocialiteException
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $data = Arr::get($request->getParsedBody(), 'data.attributes', []);

        $this->validator->make($data, [
            'username' => 'required',
            'password' => 'required',
        ])->validate();

        $response = $this->bus->dispatch(
            new GenJwtToken($data)
        );

        if ($response->getStatusCode() === 200) {
            $user = $this->app->make(UserRepository::class)->getUser();

            //绑定公众号信息
            if ($token = Arr::get($data, 'token')) {
                $this->bind->wechat($token, $user);
            }

            //绑定小程序信息
            if ($js_code = Arr::get($data, 'js_code') &&
                $iv = Arr::has($data, 'iv') &&
                $encryptedData = Arr::has($data, 'encryptedData')) {
                $this->bind->bindMiniprogram($js_code, $iv, $encryptedData, $user);
            }

            $this->events->dispatch(new Logind($user));
        }
        return json_decode($response->getBody());
    }
}
