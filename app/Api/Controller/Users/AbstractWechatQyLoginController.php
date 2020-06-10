<?php


namespace App\Api\Controller\Users;


use App\Models\SessionToken;
use Discuz\Contracts\Socialite\Factory;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

abstract class AbstractWechatQyLoginController implements RequestHandlerInterface
{

    protected $socialite;

    public $type;

    public function __construct(Factory $socialite)
    {
        $this->socialite = $socialite;
    }

    /**
     * @inheritDoc
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $request = $request->withAttribute('session', new SessionToken());
        $this->socialite->setRequest($request);
        return $this->socialite->driver($this->type)->redirect();
    }

}
