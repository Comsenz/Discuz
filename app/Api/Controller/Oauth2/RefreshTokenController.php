<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Oauth2;

use App\Api\Serializer\TokenSerializer;
use App\Passport\Repositories\AccessTokenRepository;
use App\Passport\Repositories\RefreshTokenRepository;
use DateInterval;
use Discuz\Api\Controller\AbstractResourceController;
use Discuz\Auth\Guest;
use Discuz\Foundation\Application;
use Exception;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Grant\RefreshTokenGrant;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;
use Zend\Diactoros\Response;

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

            // TODO: 刷新 token 无法获取用户 id 暂时返回 0
            TokenSerializer::setUser(new Guest());

            return json_decode((string)$response->getBody());
        } catch (Exception $e) {
            throw $e;
        }
    }
}
