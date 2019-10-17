<?php
/**
 * Created by PhpStorm.
 * User: leiyu
 * Date: 2018/4/23
 * Time: 17:36
 */

namespace App\Passport\Entitys;


use League\OAuth2\Server\Entities\AuthCodeEntityInterface;
use League\OAuth2\Server\Entities\Traits\EntityTrait;
use League\OAuth2\Server\Entities\Traits\TokenEntityTrait;
use League\OAuth2\Server\Entities\Traits\AuthCodeTrait;

class AuthCodeEntity implements AuthCodeEntityInterface
{
    use EntityTrait,TokenEntityTrait,AuthCodeTrait;

}
