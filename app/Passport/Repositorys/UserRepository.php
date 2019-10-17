<?php


namespace App\Passport\Repositorys;


use App\Passport\Entitys\UserEntity;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\UserEntityInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{

    /**
     * Get a user entity.
     *
     * @param string $username
     * @param string $password
     * @param string $grantType The grant type used
     * @param ClientEntityInterface $clientEntity
     *
     * @return UserEntityInterface
     */
    public function getUserEntityByUserCredentials($username, $password, $grantType, ClientEntityInterface $clientEntity)
    {
        // TODO: Implement getUserEntityByUserCredentials() method.

        return new UserEntity(1);
    }
}
