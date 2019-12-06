<?php
declare(strict_types=1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: CreateCircleController.php 28830 2019-09-26 09:47 chenkeke $
 */

namespace App\Api\Controller\Users;

use App\Api\Serializer\TokenSerializer;
use App\Commands\Users\GenJwtToken;
use App\Repositories\UserRepository;
use Discuz\Api\Controller\AbstractResourceController;
use Discuz\Auth\Exception\PermissionDeniedException;
use Discuz\Foundation\Application;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;
use App\Passport\Repositories\UserRepository as PassportUserRepository;

class LoginController extends AbstractResourceController
{
    public $serializer = TokenSerializer::class;

    protected $users;
    protected $bus;
    protected $app;

    public $include = ['users'];

    public function __construct(UserRepository $users, Dispatcher $bus, Application $app)
    {
        $this->users = $users;
        $this->bus = $bus;
        $this->app = $app;
    }

    /**
     * @param ServerRequestInterface $request
     * @param Document $document
     * @return array|mixed
     * @throws PermissionDeniedException
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $data = $this->bus->dispatch(new GenJwtToken(Arr::get($request->getParsedBody(), 'data.attributes')));
        $data->users = $this->app->make(PassportUserRepository::class)->getUser();
        return $data;
    }
}
