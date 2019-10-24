<?php

namespace App\Oauth;

use App\Passport\Repositories\ClientRepository;
use App\Passport\Repositories\ScopeRepository;
use App\Passport\Repositories\AccessTokenRepository;
use Zend\Diactoros\Response;
use Illuminate\Support\Arr;

class Server
{
    public $server;
    public $request;
    public $actor;
    public $password;
    public $grant;
    /**
     * Server constructor.
     */
    public function __construct(){
        // 初始化存储库
        $clientRepository = new ClientRepository(); // instance of ClientRepositoryInterface
        $scopeRepository = new ScopeRepository(); // instance of ScopeRepositoryInterface
        $accessTokenRepository = new AccessTokenRepository(); // instance of AccessTokenRepositoryInterface

        // 私钥与加密密钥
        $privateKey = 'file://'.__DIR__.'/../../private.key';
        // $privateKey = new CryptKey('file:///private.key', 'passphrase'); // 如果私钥文件有密码
        $encryptionKey = '106BktSzs0W4OvGV3S/SCsWe2WmmW+s9LusUtBdrhjc='; // 加密密钥字符串
        // $encryptionKey = Key::loadFromAsciiSafeString($encryptionKey); //如果通过 generate-defuse-key 脚本生成的字符串，可使用此方法传入

        // 初始化 server
        $server = @new \League\OAuth2\Server\AuthorizationServer(
            $clientRepository,
            $accessTokenRepository,
            $scopeRepository,
            $privateKey,
            $encryptionKey
        );

        $this->server= $server;
    }

    public function handle()
    {
        $this->grant->setRefreshTokenTTL(new \DateInterval('P1M')); // 设置刷新令牌过期时间1个月
        // 将密码授权类型添加进 server
        $this->server->enableGrantType(
            $this->grant,
            new \DateInterval('PT1H') // 设置访问令牌过期时间1小时
        );
        $response =new Response();
        try {
            // Try to respond to the request

            return $this->server->respondToAccessTokenRequest($this->request, $response);
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

