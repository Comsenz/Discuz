<?php


namespace App\Api\Controller\Group;


use App\Api\Serializer\GroupSerializer;
use App\Models\Group;
use Discuz\Api\Controller\AbstractListController;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class ListGroupsController extends AbstractListController
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
        return Group::all();
    }
}
