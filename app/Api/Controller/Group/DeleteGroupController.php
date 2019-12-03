<?php


namespace App\Api\Controller\Group;


use App\Api\Serializer\InfoSerializer;
use App\Commands\Group\DeleteGroup;
use Discuz\Api\Controller\AbstractResourceController;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class DeleteGroupController extends AbstractResourceController
{
    public $serializer = InfoSerializer::class;

    protected $bus;

    public function __construct(Dispatcher $bus)
    {
        $this->bus = $bus;
    }

    protected function data(ServerRequestInterface $request, Document $document)
    {

        return $this->bus->dispatch(new DeleteGroup(Arr::get($request->getQueryParams(), 'id'), $request->getAttribute('actor')));
    }
}
