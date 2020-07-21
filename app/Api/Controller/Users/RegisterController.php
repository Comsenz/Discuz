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
use App\Commands\Users\RegisterUser;
use App\Events\Users\RegisteredCheck;
use App\MessageTemplate\Wechat\WechatRegisterMessage;
use App\Notifications\System;
use App\Repositories\UserRepository;
use App\User\Bind;
use Discuz\Api\Controller\AbstractCreateController;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Auth\Exception\PermissionDeniedException;
use Discuz\Contracts\Setting\SettingsRepository;
use Discuz\Foundation\Application;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Contracts\Events\Dispatcher as Events;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class RegisterController extends AbstractCreateController
{
    use AssertPermissionTrait;

    protected $bus;

    protected $users;

    protected $settings;

    protected $app;

    protected $bind;

    protected $events;

    public function __construct(Dispatcher $bus, UserRepository $users, SettingsRepository $settings, Application $app, Bind $bind, Events $events)
    {
        $this->bus = $bus;
        $this->users = $users;
        $this->settings = $settings;
        $this->app = $app;
        $this->bind = $bind;
        $this->events = $events;
    }

    public $serializer = TokenSerializer::class;

    /**
     * {@inheritdoc}
     * @throws PermissionDeniedException
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $this->assertPermission((bool)$this->settings->get('register_close'));

        $attributes = Arr::get($request->getParsedBody(), 'data.attributes', []);
        $attributes['register_ip'] = ip($request->getServerParams());
        $attributes['register_port'] = Arr::get($request->getServerParams(), 'REMOTE_PORT');

        $user = $this->bus->dispatch(
            new RegisterUser($request->getAttribute('actor'), $attributes)
        );

        if ($token = Arr::get($attributes, 'token')) {
            $this->bind->wechat($token, $user);
            // 判断是否开启了注册审核
            if (!(bool)$this->settings->get('register_validate')) {
                // 在注册绑定微信后 发送注册微信通知
                $user->notify(new System(WechatRegisterMessage::class));
            }
        }

        if ($mobile = Arr::get($attributes, 'mobile')) {
            $this->bind->mobile($mobile, $user);
        }

        $this->events->dispatch(new RegisteredCheck($user));

        $response = $this->bus->dispatch(
            new GenJwtToken(Arr::only($attributes, 'username'))
        );

        return json_decode($response->getBody());
    }
}
