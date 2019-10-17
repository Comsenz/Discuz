<?php
/**
 * Created by PhpStorm.
 * User: leiyu
 * Date: 2018/4/23
 * Time: 17:55
 */

namespace App\Passport\Entitys;


use League\OAuth2\Server\Entities\Traits\EntityTrait;
use League\OAuth2\Server\Entities\UserEntityInterface;

class UserEntity implements UserEntityInterface
{
    use EntityTrait;

    public function __construct($identifier)
    {
        $this->setIdentifier($identifier);
    }
}
