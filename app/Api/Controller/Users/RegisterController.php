<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: RegisterController.php xxx 2019-11-15 11:03:00 LiuDongdong $
 */

namespace App\Api\Controller\Users;

use App\Api\Controller\Oauth2\AccessTokenController;
use App\Api\Serializer\TokenSerializer;
use App\Commands\Users\RegisterUser;
use App\Repositories\UserRepository;
use Discuz\Api\Client;
use Discuz\Api\Controller\AbstractCreateController;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class RegisterController extends AbstractCreateController
{
    protected $bus;
    protected $users;
    protected $apiClient;

    public function __construct(Dispatcher $bus, UserRepository $users, Client $apiClient)
    {
        $this->bus = $bus;
        $this->users = $users;
        $this->apiClient = $apiClient;
    }

    public $serializer = TokenSerializer::class;

    /**
     * {@inheritdoc}
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $data = $request->getParsedBody()->get('data', []);

        $this->bus->dispatch(
            new RegisterUser($request->getAttribute('actor'), $data)
        );

        $username = Arr::get($data,'attributes.username', null);
        $password = Arr::get($data,'attributes.password', null);

        //创建 token
        $param = [
            'grant_type' => 'password',
            'client_id' => '',
            'client_secret' => '',
            'scope' => '',
            'username' => $username,
            'password' => $password
        ];

        $response = $this->apiClient->send(AccessTokenController::class, null, [], $param);

        return json_decode((string)$response->getBody());
    }
}
