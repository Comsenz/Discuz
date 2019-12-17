<?php

namespace App\Api\Controller\Users;


use App\Api\Serializer\UserProfileSerializer;
use App\Commands\Users\UpdateUser;
use Discuz\Api\Controller\AbstractResourceController;
use Illuminate\Contracts\Bus\Dispatcher;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;
use Illuminate\Support\Arr;

class UpdateUserController extends AbstractResourceController
{
    public $serializer = UserProfileSerializer::class;

    protected $bus;

    public function __construct(Dispatcher $bus)
    {
        $this->bus = $bus;
    }


    protected function data(ServerRequestInterface $request, Document $document)
    {
        return $this->bus->dispatch(new UpdateUser(Arr::get($request->getQueryParams(), 'id'), $request->getParsedBody(), $request->getAttribute('actor')));
    }
}
