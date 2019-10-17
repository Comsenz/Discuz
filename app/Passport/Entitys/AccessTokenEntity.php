<?php
/**
 * Created by PhpStorm.
 * User: leiyu
 * Date: 2018/4/23
 * Time: 17:05
 */

namespace App\Passport\Entitys;


use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\Traits\AccessTokenTrait;
use League\OAuth2\Server\Entities\Traits\EntityTrait;
use League\OAuth2\Server\Entities\Traits\TokenEntityTrait;

class AccessTokenEntity implements AccessTokenEntityInterface
{
    use AccessTokenTrait, EntityTrait, TokenEntityTrait;

}
