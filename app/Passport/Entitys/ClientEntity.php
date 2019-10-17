<?php
/**
 * Created by PhpStorm.
 * User: leiyu
 * Date: 2018/4/23
 * Time: 16:40
 */

namespace App\Passport\Entitys;


use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\Traits\ClientTrait;
use League\OAuth2\Server\Entities\Traits\EntityTrait;

class ClientEntity implements ClientEntityInterface
{
    use ClientTrait,EntityTrait;


    /**
     * @param $name
     */
    public function setName($name) {
        $this->name = $name;
    }

    /**
     * @param $redirectUri
     */
    public function setRedirectUri($redirectUri) {
        $this->redirectUri = $redirectUri;
    }
}
