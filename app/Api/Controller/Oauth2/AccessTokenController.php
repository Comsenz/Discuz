<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Oauth2;

use App\Passport\Repositories\AccessTokenRepository;
use App\Passport\Repositories\ClientRepository;
use App\Passport\Repositories\RefreshTokenRepository;
use App\Passport\Repositories\ScopeRepository;
use App\Passport\Repositories\UserRepository;
use DateInterval;
use Discuz\Foundation\Application;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Grant\PasswordGrant;
use Zend\Diactoros\Response;

class AccessTokenController implements RequestHandlerInterface
{
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws \Exception
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $userRepository = $this->app->make(UserRepository::class); // instance of UserRepositoryInterface
        $refreshTokenRepository = new RefreshTokenRepository(); // instance of RefreshTokenRepositoryInterface

        $grant = new PasswordGrant(
            $userRepository,
            $refreshTokenRepository
        );

        $grant->setRefreshTokenTTL(new DateInterval(AccessTokenRepository::REFER_TOKEN_EXP)); // refresh tokens will expire after 2 month

        $server = $this->app->make(AuthorizationServer::class);
        $server->enableGrantType(
            $grant,
            new DateInterval(AccessTokenRepository::TOKEN_EXP) // access tokens will expire after 1 mouth
        );

        $response = new Response();
        if ($request->getParsedBody() instanceof Collection) {
            $request = $request->withParsedBody($request->getParsedBody()->toArray());
        }

        return $server->respondToAccessTokenRequest($request, $response);
    }
}
