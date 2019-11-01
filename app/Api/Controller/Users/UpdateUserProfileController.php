<?php
declare(strict_types=1);

namespace App\Api\Controller\Users;


use App\Api\Serializer\UserPorfileSerializer;
use Discuz\Api\Controller\AbstractResourceController;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;
use Illuminate\Support\Arr;
use App\Commands\UserProfile\UpdateUserProfile;
use App\Commands\UserProfile\UserProfile;

class UpdateUserProfileController extends AbstractResourceController
{
    public $serializer = UserPorfileSerializer::class;


    /**
     * @param ServerRequestInterface $request
     * @param Document $document
     */
    public function data(ServerRequestInterface $request, Document $document)
    {
        // 获取当前用户
        $actor = $request->getAttribute('actor');
        $id = Arr::get($request->getQueryParams(), 'id');
        // 获取请求的参数
        $inputs = $request->getParsedBody();
        // 获取请求的IP
        $ipAddress = Arr::get($request->getServerParams(), 'REMOTE_ADDR', '127.0.0.1');
        $data = $this->bus->dispatch(
            new UpdateUserProfile($id,$actor, $inputs->toArray(), $ipAddress)
        );
        $data = $this->bus->dispatch(
            new UserProfile($id, $actor)
        );
        
        return $data;
        
         
    }
}