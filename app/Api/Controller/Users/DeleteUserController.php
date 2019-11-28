<?php

namespace App\Api\Controller\Users;


use App\Commands\Users\DeleteUsers;
use Discuz\Api\Controller\AbstractDeleteController;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;


class DeleteUserController extends AbstractDeleteController
{

    protected $bus;

    public function __construct(Dispatcher $bus)
    {
        $this->bus = $bus;
    }

    public function delete(ServerRequestInterface $request)
    {
        $this->bus->dispatch(
            new DeleteUsers(Arr::get($request->getQueryParams(), 'id'), $request->getAttribute('actor'))
        );
    }
}
