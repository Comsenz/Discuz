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
use App\Models\User;
use App\Passport\Repositories\UserRepository;
use App\User\Bind;
use App\User\Bound;
use Discuz\Api\Controller\AbstractResourceController;
use Discuz\Foundation\Application;
use Discuz\Socialite\Exception\SocialiteException;
use Exception;
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

    /**
     * @var Events
     */
    protected $events;

    /**
     * @var Bind $bind
     */
    protected $bind;

    /**
     * @var Bound
     */
    protected $bound;

    public $include = ['users'];

    public function __construct(UserRepository $users, Dispatcher $bus, Application $app, Validator $validator, Events $events, Bind $bind, Bound $bound)
    {
        $this->users = $users;
        $this->bus = $bus;
        $this->app = $app;
        $this->validator = $validator;
        $this->events = $events;
        $this->bind = $bind;
        $this->bound = $bound;
    }

    /**
     * @param ServerRequestInterface $request
     * @param Document $document
     * @return array|mixed
     * @throws ValidationException
     * @throws SocialiteException
     * @throws Exception
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $data = Arr::get($request->getParsedBody(), 'data.attributes', []);

        $this->validator->make($data, [
            'mobileToken' => 'filled',
            'username' => 'required',
            'password' => 'required',
        ])->validate();

        $response = $this->bus->dispatch(
            new GenJwtToken($data)
        );

        $accessToken = json_decode($response->getBody());

        if ($response->getStatusCode() === 200) {
            /** @var User $user */
            $user = $this->app->make(UserRepository::class)->getUser();

            $rebind = Arr::get($data, 'rebind', 0);

            // 绑定公众号信息
            if ($token = Arr::get($data, 'token')) {
                $this->bind->withToken($token, $user, $rebind);
            }

            // 绑定小程序信息
            $js_code = Arr::get($data, 'js_code');
            $iv = Arr::get($data, 'iv');
            $encryptedData = Arr::get($data, 'encryptedData');
            if ($js_code && $iv  && $encryptedData) {
                $this->bind->bindMiniprogram($js_code, $iv, $encryptedData, $rebind, $user);
            }

            // 绑定手机号
            if ($mobileToken = Arr::get($data, 'mobileToken')) {
                $this->bind->mobile($mobileToken, $user);
            }

            $this->events->dispatch(new Logind($user));
        }

        return $accessToken;
    }
}
