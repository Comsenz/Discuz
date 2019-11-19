<?php


namespace App\Commands\Users;


use App\Api\Controller\Oauth2\AccessTokenController;
use App\Events\Users\UserVerify;
use App\Passport\Repositories\UserRepository;
use Discuz\Api\Client;
use Discuz\Foundation\Application;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Arr;

class GenJwtToken
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function handle(Client $apiClient, Application $app, Dispatcher $events) {
        $param = [
            'grant_type' => 'password',
            'client_id' => '',
            'client_secret' => '',
            'scope' => '',
            'username' => Arr::get($this->data, 'username', ''),
            'password' => Arr::get($this->data, 'password', '')
        ];

        $response = $apiClient->send(AccessTokenController::class, null, [], $param);

        $events->dispatch(new UserVerify($app->make(UserRepository::class)->getUser(), $this->data));

        return json_decode((string)$response->getBody());
    }
}
