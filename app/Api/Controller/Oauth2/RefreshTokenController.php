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

use App\Api\Serializer\TokenSerializer;
use App\Passport\Repositories\AccessTokenRepository;
use App\Passport\Repositories\RefreshTokenRepository;
use DateInterval;
use Discuz\Api\Controller\AbstractResourceController;
use Discuz\Foundation\Application;
use Exception;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Laminas\Diactoros\Response;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Grant\RefreshTokenGrant;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class RefreshTokenController extends AbstractResourceController
{
    public $serializer = TokenSerializer::class;

    /**
     * @var Application
     */
    protected $app;

    protected $events;

    public function __construct(Application $app, Dispatcher $events)
    {
        $this->app = $app;
        $this->events = $events;
    }

    /**
     * @inheritDoc
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $refreshTokenRepository = new RefreshTokenRepository();

        // Setup the authorization server
        $server = $this->app->make(AuthorizationServer::class);

        $grant = new RefreshTokenGrant($refreshTokenRepository);
        $grant->setRefreshTokenTTL(new DateInterval(AccessTokenRepository::REFER_TOKEN_EXP)); // new refresh tokens will expire after 1 month

        // Enable the refresh token grant on the server
        $server->enableGrantType(
            $grant,
            new DateInterval(AccessTokenRepository::TOKEN_EXP) // new access tokens will expire after an hour
        );

        if ($request->getParsedBody() instanceof Collection) {
            $data = $request->getParsedBody()->get('data');
            $request = $request->withParsedBody(
                [
                    'grant_type' => 'refresh_token',
                    'refresh_token' => Arr::get($data, 'attributes.refresh_token'),
                    'client_id' => '',
                    'client_secret' => '',
                    'scope' => '',
                ]
            );
        }
        try {
            $response = $server->respondToAccessTokenRequest($request, new Response());

            return json_decode((string)$response->getBody());
        } catch (Exception $e) {
            throw $e;
        }
    }
}
