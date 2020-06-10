<?php


namespace App\Api\Controller\Users;


use App\Models\SessionToken;
use Discuz\Contracts\Socialite\Factory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

abstract class AbstractQQLoginController implements RequestHandlerInterface
{

    protected $socialite;

    public $type;

    public function __construct(Factory $socialite)
    {
        $this->socialite = $socialite;
    }


}
