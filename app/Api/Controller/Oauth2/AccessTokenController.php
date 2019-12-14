<?php


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
        // Init our repositories
        $clientRepository = new ClientRepository(); // instance of ClientRepositoryInterface
        $scopeRepository = new ScopeRepository(); // instance of ScopeRepositoryInterface
        $accessTokenRepository = new AccessTokenRepository(); // instance of AccessTokenRepositoryInterface
        $userRepository = $this->app->make(UserRepository::class); // instance of UserRepositoryInterface
        $refreshTokenRepository = new RefreshTokenRepository(); // instance of RefreshTokenRepositoryInterface

        // Path to public and private keys
        $privateKey = storage_path('cert/private.key');

        if(Str::startsWith($this->app->config('key'), 'base64:')) {
            $encryptionKey = substr($this->app->config('key'), 7); // generate using base64_encode(random_bytes(32))
        } else {
            $encryptionKey = base64_encode(random_bytes(32));
        }

        // Setup the authorization server
        $server = new AuthorizationServer(
            $clientRepository,
            $accessTokenRepository,
            $scopeRepository,
            $privateKey,
            $encryptionKey
        );

        $grant = new PasswordGrant(
            $userRepository,
            $refreshTokenRepository
        );

        $grant->setRefreshTokenTTL(new DateInterval(AccessTokenRepository::REFER_TOKEN_EXP)); // refresh tokens will expire after 2 month

        $server->enableGrantType(
            $grant,
            new DateInterval(AccessTokenRepository::TOKEN_EXP) // access tokens will expire after 1 mouth
        );

        $response = new Response();
        if($request->getParsedBody() instanceof Collection) {
            $request = $request->withParsedBody($request->getParsedBody()->toArray());
        }

        return $server->respondToAccessTokenRequest($request, $response);
    }
}
