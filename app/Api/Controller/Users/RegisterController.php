<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: RegisterController.php xxx 2019-11-15 11:03:00 LiuDongdong $
 */

namespace App\Api\Controller\Users;

use App\Api\Serializer\TokenSerializer;
use App\Commands\Users\GenJwtToken;
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

        $params = [
            'username' => Arr::get($data,'attributes.username', null),
            'password' => '',
        ];

        return $this->bus->dispatch(new GenJwtToken($params));
    }
}
