<?php
/**
 * @author      Alex Bilbie <hello@alexbilbie.com>
 * @copyright   Copyright (c) Alex Bilbie
 * @license     http://mit-license.org/
 *
 * @link        https://github.com/thephpleague/oauth2-server
 */
namespace App\Passport\Repositories;

use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;
use App\Passport\Entities\UserEntity;
use App\Models\User;

class UserRepository implements UserRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function getUserEntityByUserCredentials(
        $username,
        $password,
        $grantType,
        ClientEntityInterface $clientEntity
    ) {
        $user=User::where('username',$username)->first();
        $userpwd=$user->password;
        if(User::unsetUserPasswordAttr($password,$userpwd)){
            return new UserEntity($user->id);
        }
        
    }
}