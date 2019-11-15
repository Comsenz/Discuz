<?php
declare(strict_types=1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: CreateCircleController.php 28830 2019-09-26 09:47 chenkeke $
 */

namespace App\Api\Controller\Users;

use App\Api\Controller\Oauth2\AccessTokenController;
use App\Api\Serializer\TokenSerializer;
use App\Repositories\UserRepository;
use Discuz\Api\Client;
use Discuz\Api\Controller\AbstractResourceController;
use Discuz\Auth\Exception\PermissionDeniedException;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class LoginController extends AbstractResourceController
{
    protected $users;
    protected $apiClient;

    public function __construct(UserRepository $users, Client $apiClient)
    {
        $this->users = $users;
        $this->apiClient = $apiClient;
    }

    public $serializer = TokenSerializer::class;

    /**
     * @param ServerRequestInterface $request
     * @param Document $document
     * @return array|mixed
     * @throws PermissionDeniedException
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $body = Arr::get($request->getParsedBody(), 'data.attributes');
//        $ipAddress = Arr::get($request->getServerParams(), 'REMOTE_ADDR', '127.0.0.1');
//
//
        $username = Arr::get($body,'username', null);
        $password = Arr::get($body,'password', null);


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
