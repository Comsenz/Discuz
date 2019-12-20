<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Passport\Entities;

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
