<?php
declare(strict_types=1);

namespace App\Api\Controller\Users;


use App\Api\Serializer\UserSerializer;
use App\Models\User;
use Discuz\Api\Controller\AbstractDeleteController;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;
use Illuminate\Support\Arr;


class DeleteUsersController extends AbstractDeleteController
{
    
    /**
     * @param ServerRequestInterface $request
     * @param Document $document
     */
    public function data(ServerRequestInterface $request, Document $document)
    {
        // TODO: Implement data() method.
    }

    public function delete(ServerRequestInterface $request)
    {
        // TODO: Implement delete() method.
        
        $data = $request->getParsedBody();
        // dd($request);
        $users = User::wherein("id",$data['user'])->delete();
        // dd($users);
        return $users;
    }
}