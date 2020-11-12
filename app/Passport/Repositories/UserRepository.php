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

namespace App\Passport\Repositories;

use App\Api\Serializer\TokenSerializer;
use App\Events\Users\Logining;
use App\Passport\Entities\UserEntity;
use App\Repositories\UserRepository as RepositoriesUserRepository;
use Discuz\Auth\Exception\LoginFailedException;
use Illuminate\Contracts\Events\Dispatcher;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\UserEntityInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;
use App\Commands\Users\GenJwtToken;

class UserRepository implements UserRepositoryInterface
{
    protected $users;

    protected static $user;

    protected $events;

    public function __construct(RepositoriesUserRepository $users, Dispatcher $dispatcher)
    {
        $this->users = $users;
        $this->events = $dispatcher;
    }

    /**
     * @param string $username
     * @param string $password
     * @param string $grantType
     * @param ClientEntityInterface $clientEntity
     * @return UserEntity|UserEntityInterface|null
     * @throws LoginFailedException
     */
    public function getUserEntityByUserCredentials($username, $password, $grantType, ClientEntityInterface $clientEntity)
    {
        $id = GenJwtToken::getUid();
        $where = $id ? compact('id') : compact('username');
        $user = $this->users->findByIdentification($where);

        if (! $user && ! $user = $this->users->findByIdentification(['mobile'=>$username])) {
            throw new LoginFailedException;
        }

        $this->events->dispatch(new Logining($user, $username, $password));

        static::$user = $user;

        TokenSerializer::setUser($user);

        return new UserEntity($user['id']);
    }

    public function getUser()
    {
        return static::$user;
    }
}
