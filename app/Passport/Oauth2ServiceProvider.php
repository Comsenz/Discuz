<?php


namespace App\Passport;


use App\Passport\Repositories\AccessTokenRepository;
use App\Passport\Repositories\ClientRepository;
use App\Passport\Repositories\RefreshTokenRepository;
use App\Passport\Repositories\ScopeRepository;
use Discuz\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use League\OAuth2\Server\AuthorizationServer;

class Oauth2ServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(AuthorizationServer::class, function(Application $app) {
            // Init our repositories
            $clientRepository = new ClientRepository();
            $accessTokenRepository = new AccessTokenRepository();
            $scopeRepository = new ScopeRepository();

            // Path to public and private keys
            $privateKey = storage_path('cert/private.key');
            //$privateKey = new CryptKey('file://path/to/private.key', 'passphrase'); // if private key has a pass phrase
            $encryptionKey = substr($app->config('key'), 7); // generate using base64_encode(random_bytes(32))

            // Setup the authorization server
            return  new AuthorizationServer(
                $clientRepository,
                $accessTokenRepository,
                $scopeRepository,
                $privateKey,
                $encryptionKey
            );
        });
    }
}
