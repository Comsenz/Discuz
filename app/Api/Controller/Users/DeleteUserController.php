<?php

namespace App\Api\Controller\Users;


use App\Api\Serializer\ErrorUserSerializer;
use App\Commands\Users\DeleteUsers;
use Discuz\Api\Controller\AbstractResourceController;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;


class DeleteUserController extends AbstractResourceController
{
    public $serializer = ErrorUserSerializer::class;

    protected $bus;

    public function __construct(Dispatcher $bus)
    {
        $this->bus = $bus;
    }

    protected function data(ServerRequestInterface $request, Document $document)
    {
        return $this->bus->dispatch(
            new DeleteUsers(Arr::get($request->getQueryParams(), 'id'), $request->getAttribute('actor'))
        );
    }
}
