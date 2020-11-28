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

namespace App\Passport\Repositories;

use App\Passport\Entities\ClientEntity;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\Traits\ClientTrait;
use League\OAuth2\Server\Entities\Traits\EntityTrait;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;

class ClientRepository implements ClientRepositoryInterface
{
    use ClientTrait, EntityTrait;

    /**
     * Get a client.
     *
     * @param string $clientIdentifier The client's identifier
     *
     * @return ClientEntityInterface|null
     */
    public function getClientEntity($clientIdentifier)
    {
        $client = new ClientEntity();

        $client->setIdentifier($clientIdentifier);
        $client->setRedirectUri('/');
        $client->setConfidential();

        return $client;
    }

    /**
     * Validate a client's secret.
     *
     * @param string $clientIdentifier The client's identifier
     * @param null|string $clientSecret The client's secret (if sent)
     * @param null|string $grantType The type of grant the client is using (if sent)
     *
     * @return bool
     */
    public function validateClient($clientIdentifier, $clientSecret, $grantType)
    {
        // TODO: Implement validateClient() method.
        return true;
    }
}
