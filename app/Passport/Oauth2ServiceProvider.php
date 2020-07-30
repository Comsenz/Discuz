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

namespace App\Passport;

use App\Passport\Repositories\AccessTokenRepository;
use App\Passport\Repositories\ClientRepository;
use App\Passport\Repositories\ScopeRepository;
use Discuz\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\CryptKey;

class Oauth2ServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(AuthorizationServer::class, function (Application $app) {
            // Init our repositories
            $clientRepository = new ClientRepository();
            $accessTokenRepository = new AccessTokenRepository();
            $scopeRepository = new ScopeRepository();

            // Path to public and private keys
            $privateKey = new CryptKey(storage_path('cert/private.key'), '', false); // if private key has a pass phrase
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
