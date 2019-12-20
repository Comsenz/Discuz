<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Users;

use App\Api\Serializer\TokenSerializer;
use App\Commands\Users\GenJwtToken;
use App\Repositories\UserRepository;
use Discuz\Api\Controller\AbstractResourceController;
use Discuz\Foundation\Application;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

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
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        return $this->bus->dispatch(new GenJwtToken(Arr::get($request->getParsedBody(), 'data.attributes')));
    }
}
