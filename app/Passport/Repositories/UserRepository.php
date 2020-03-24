<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Passport\Repositories;

use App\Api\Serializer\TokenSerializer;
use App\Events\Users\Logind;
use App\Events\Users\Logining;
use App\Models\UserLoginFailLog;
use Discuz\Auth\Exception\LoginFailedException;
use Discuz\Auth\Exception\LoginFailuresTimesToplimitException;
use Illuminate\Contracts\Events\Dispatcher;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\UserEntityInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;
use App\Passport\Entities\UserEntity;
use App\Repositories\UserRepository as RepositoriesUserRepository;

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
        $user = $this->users->findByIdentification(compact('username'));

        if (! $user) {
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
