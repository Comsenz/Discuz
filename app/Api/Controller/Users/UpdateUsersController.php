<?php
declare(strict_types=1);

namespace App\Api\Controller\Users;


use App\Api\Serializer\UserSerializer;
use App\Models\User;
use Discuz\Api\Controller\AbstractResourceController;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;
use Illuminate\Support\Arr;


class UpdateUsersController extends AbstractResourceController
{
    public $serializer = UserSerializer::class;

    /**
     * @param ServerRequestInterface $request
     * @param Document $document
     */
    public function data(ServerRequestInterface $request, Document $document)
    {
        // TODO: Implement data() method.
        
        $data = $request->getParsedBody();
        // dd($data);
        $users = User::wherein("id",$data['user'])->update($data['operation']);
        // dd($users);
        return $users;
    }
}