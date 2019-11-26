<?php
/**
 * @author      Alex Bilbie <hello@alexbilbie.com>
 * @copyright   Copyright (c) Alex Bilbie
 * @license     http://mit-license.org/
 *
 * @link        https://github.com/thephpleague/oauth2-server
 */
namespace App\Passport\Repositories;

use App\Api\Serializer\TokenSerializer;
use App\Events\Users\UserVerify;
use Discuz\Auth\Exception\PermissionDeniedException;
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

    public function __construct(RepositoriesUserRepository $users)
    {
        $this->users = $users;
    }


    /**
     * @param string $username
     * @param string $password
     * @param string $grantType
     * @param ClientEntityInterface $clientEntity
     * @return UserEntity|UserEntityInterface|null
     * @throws PermissionDeniedException
     */
    public function getUserEntityByUserCredentials($username, $password, $grantType, ClientEntityInterface $clientEntity)
    {
        $user = $this->users->findByIdentification(compact('username'));

        if ($password && (! $user || ! $user->checkPassword($password))) {
            throw new PermissionDeniedException;
        }
        static::$user = $user;

        TokenSerializer::setUser($user);

        return new UserEntity($user['id']);
    }

    public function getUser() {
        return static::$user;
    }
}
