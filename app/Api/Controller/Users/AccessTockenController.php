<?php

namespace App\Api\Controller\Users;


use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use App\Api\Controller\Users\Server;
use Zend\Diactoros\Response;
use Psr\Http\Server\RequestHandlerInterface;
use App\Passport\Repositories\UserRepository;
use App\Passport\Repositories\RefreshTokenRepository;
use Illuminate\Support\Arr;

class AccessTockenController implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $refreshTokenRepository = new RefreshTokenRepository(); // instance of RefreshTokenRepositoryInterface
        // dd($request);
        $server = new Server;
        $server=$server->server;
        $grant = new \League\OAuth2\Server\Grant\RefreshTokenGrant($refreshTokenRepository);
        $grant->setRefreshTokenTTL(new \DateInterval('P1M')); // 设置刷新令牌过期时间1个月
        // dd($grant);
        // 将密码授权类型添加进 server
        $server->enableGrantType(
            $grant,
            new \DateInterval('PT1H') // 设置访问令牌过期时间1小时
        );
        // dd($server);
        $response =new Response();
       
        try {
            // Try to respond to the request
            return $server->respondToAccessTokenRequest($request, $response);
            
        } catch (\League\OAuth2\Server\Exception\OAuthServerException $exception) {
        
            // All instances of OAuthServerException can be formatted into a HTTP response
            return $exception->generateHttpResponse($response);
            
        } catch (\Exception $exception) {
        
            // Unknown exception
            $body = new Stream('php://temp', 'r+');
            $body->write($exception->getMessage());
            return $response->withStatus(500)->withBody($body);
            
        }
    }
}