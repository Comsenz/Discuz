<?php
/**
 * @author      Alex Bilbie <hello@alexbilbie.com>
 * @copyright   Copyright (c) Alex Bilbie
 * @license     http://mit-license.org/
 *
 * @link        https://github.com/thephpleague/oauth2-server
 */
namespace App\Passport\Repositories;

use Discuz\Auth\Exception\PermissionDeniedException;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\UserEntityInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;
use App\Passport\Entities\UserEntity;
use App\Models\User;

class UserRepository implements UserRepositoryInterface
{

    protected $users;

    public function __construct(\App\Repositories\UserRepository $users)
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

        if (! $user || ! $user->checkPassword($password)) {
            throw new PermissionDeniedException;
        }

        return new UserEntity($user['id']);
    }
}
