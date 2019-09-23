<?php


namespace App\Api\Controller;


use App\Api\Serializer\UserSerializer;
use App\Models\User;
use Discuz\Api\Controller\AbstractListController;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class ListUsersController extends AbstractListController
{
    public $serializer = UserSerializer::class;

    public function data(ServerRequestInterface $request, Document $document)
    {
        // TODO: Implement data() method.

        return User::all();
    }
}
