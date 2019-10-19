<?php
/**
 * @author      Alex Bilbie <hello@alexbilbie.com>
 * @copyright   Copyright (c) Alex Bilbie
 * @license     http://mit-license.org/
 *
 * @link        https://github.com/thephpleague/oauth2-server
 */
namespace App\Passport\Repositories;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;
use App\Passport\Entities\ClientEntity;
class ClientRepository implements ClientRepositoryInterface
{
    const CLIENT_NAME = 'discuz';
    const REDIRECT_URI = 'http://www.discuz.com/api';
    /**
     * {@inheritdoc}
     */
    public function getClientEntity($clientIdentifier)
    {
        $client = new ClientEntity();
        $client->setIdentifier($clientIdentifier);
        $client->setName(self::CLIENT_NAME);
        $client->setRedirectUri(self::REDIRECT_URI);
        $client->setConfidential();
        return $client;
    }
    /**
     * {@inheritdoc}
     */
    public function validateClient($clientIdentifier, $clientSecret, $grantType)
    {
        $clients = [
            'myawesomeapp' => [
                'secret'          => password_hash('abc123', PASSWORD_BCRYPT),
                'name'            => self::CLIENT_NAME,
                'redirect_uri'    => self::REDIRECT_URI,
                'is_confidential' => true,
            ],
        ];
        // Check if client is registered
        if (array_key_exists($clientIdentifier, $clients) === false) {
            return;
        }
        if (
            $clients[$clientIdentifier]['is_confidential'] === true
            && password_verify($clientSecret, $clients[$clientIdentifier]['secret']) === false
        ) {
            return;
        }
    }
}