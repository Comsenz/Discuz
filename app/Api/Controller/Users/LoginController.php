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
use App\Commands\Users\GenJwtToken;
use App\Repositories\UserRepository;
use Discuz\Api\Client;
use Discuz\Api\Controller\AbstractResourceController;
use Discuz\Auth\Exception\PermissionDeniedException;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class LoginController extends AbstractResourceController
{
    protected $users;
    protected $bus;

    public function __construct(UserRepository $users, Dispatcher $bus)
    {
        $this->users = $users;
        $this->bus = $bus;
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

        $username = Arr::get($body,'username', null);
        $password = Arr::get($body,'password', null);

        //创建 token
        $params = [
            'username' => $username,
            'password' => $password
        ];

        return $this->bus->dispatch(new GenJwtToken($params));
    }
}
