<?php


namespace App\Api\Controller\Users;


use App\Api\Serializer\UserProfileSerializer;
use Discuz\Api\Controller\AbstractResourceController;
use Discuz\Auth\AssertPermissionTrait;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class UnbindWechatController extends AbstractResourceController
{

    use AssertPermissionTrait;

    public $serializer = UserProfileSerializer::class;


    /**
     * @param ServerRequestInterface $request
     * @param Document $document
     * @return mixed
     * @throws \Discuz\Auth\Exception\PermissionDeniedException
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $actor = $request->getAttribute('actor');
        $id = Arr::get($request->getQueryParams(), 'id');

        $this->assertPermission($actor->id == $id);

        $actor->wechat && $actor->wechat->delete();

        return $actor;
    }
}
