<?php


namespace App\Api\Controller\Group;


use App\Api\Serializer\GroupSerializer;
use App\Commands\Group\CreateGroup;
use Discuz\Api\Controller\AbstractCreateController;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class CreateGroupController extends AbstractCreateController
{

    public $serializer = GroupSerializer::class;

    /**
     * Get the data to be serialized and assigned to the response document.
     *
     * @param ServerRequestInterface $request
     * @param Document $document
     * @return mixed
     */
    public function data(ServerRequestInterface $request, Document $document)
    {
        return $this->bus->dispatch(new CreateGroup($request->getAttribute('actor'), $request->getParsedBody()));
    }
}
