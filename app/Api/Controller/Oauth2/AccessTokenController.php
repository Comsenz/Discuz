<?php

/**
 * Copyright (C) 2020 Tencent Cloud.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace App\Api\Controller\Oauth2;

use App\Passport\Repositories\AccessTokenRepository;
use App\Passport\Repositories\RefreshTokenRepository;
use App\Passport\Repositories\UserRepository;
use DateInterval;
use Discuz\Foundation\Application;
use Illuminate\Support\Collection;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Grant\PasswordGrant;
use Laminas\Diactoros\Response;

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
