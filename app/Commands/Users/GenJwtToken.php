<?php


namespace App\Commands\Users;


use App\Api\Controller\Oauth2\AccessTokenController;
use Discuz\Api\Client;
use Illuminate\Support\Arr;

class GenJwtToken
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function handle(Client $apiClient) {
        $param = [
            'grant_type' => 'password',
            'client_id' => '',
            'client_secret' => '',
            'scope' => '',
            'username' => Arr::get($this->data, 'username', ''),
            'password' => Arr::get($this->data, 'password', '')
        ];

        $response = $apiClient->send(AccessTokenController::class, null, [], $param);

        return json_decode((string)$response->getBody());
    }
}
