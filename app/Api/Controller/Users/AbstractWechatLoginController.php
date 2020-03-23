<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Users;

use App\Models\SessionToken;
use Discuz\Contracts\Socialite\Factory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

abstract class AbstractWechatLoginController implements RequestHandlerInterface
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
