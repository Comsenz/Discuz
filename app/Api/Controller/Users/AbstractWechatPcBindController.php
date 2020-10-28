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
use App\Models\SessionToken;
use App\Models\User;
use App\Models\UserWechat;
use App\Settings\SettingsRepository;
use App\User\Bound;
use Discuz\Api\Controller\AbstractResourceController;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Contracts\Socialite\Factory;
use Exception;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Contracts\Cache\Repository;
use Illuminate\Contracts\Events\Dispatcher as Events;
use Illuminate\Contracts\Validation\Factory as ValidationFactory;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

abstract class AbstractWechatPcBindController extends AbstractResourceController
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
     * @throws Exception
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $sessionId = Arr::get($request->getQueryParams(), 'sessionId');

        $request = $request->withAttribute('session', new SessionToken())->withAttribute('sessionId', $sessionId);

        $this->validation->make([
            'code' => Arr::get($request->getQueryParams(), 'code'),
            'sessionId' => Arr::get($request->getQueryParams(), 'sessionId'),
            'session_token' => Arr::get($request->getQueryParams(), 'session_token'),
        ], [
            'code' => 'required',
            'sessionId' => 'required',
            'session_token' => 'required',
        ])->validate();

        /** @var User $actor */
        $sessionToken = SessionToken::query()->where('token', Arr::get($request->getQueryParams(), 'session_token'))->first();
        if (! empty($sessionToken)) {
            /** @var SessionToken $sessionToken */
            $actor = User::query()->where('id', $sessionToken->user_id)->first();
        }

        if (! isset($actor)) {
            throw new Exception('not_found_user');
        }

        $this->socialite->setRequest($request);

        $driver = $this->socialite->driver($this->getDriver());

        $wxuser = $driver->user();

        /** @var UserWechat $wechatUser */
        $wechatUser = UserWechat::where($this->getType(), $wxuser->getId())->orWhere('unionid', Arr::get($wxuser->getRaw(), 'unionid'))->first();

        if (! $wechatUser || ! $wechatUser->user) {
            if (! $actor->isGuest() && is_null($actor->wechat)) {
                // 登陆用户且没有绑定||换绑微信 添加微信绑定关系
                $wechatUser->user_id = $actor->id;
                $wechatUser->setRelation('user', $actor);
                $wechatUser->save();
            }
        } else {
            // 登陆用户和微信绑定不同时，微信已绑定用户，抛出异常
            if (! $actor->isGuest() && $actor->id != $wechatUser->user_id) {
                throw new Exception('account_has_been_bound');
            }

            // 登陆用户和微信绑定相同，更新微信信息
            $wechatUser->setRawAttributes($this->fixData($wxuser->getRaw(), $wechatUser->user));
            $wechatUser->save();
        }
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
