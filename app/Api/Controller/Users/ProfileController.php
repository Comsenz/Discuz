<?php


namespace App\Api\Controller\Users;


use App\Api\Serializer\UserSerializer;
use App\Models\User;
use Discuz\Api\Controller\AbstractResourceController;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class ProfileController extends AbstractResourceController
{

    public $serializer = UserSerializer::class;

    public $optionalInclude = ['wechat', 'groups'];


    /**
     * @param ServerRequestInterface $request
     * @param Document $document
     * @return mixed
     * @throws \Tobscure\JsonApi\Exception\InvalidParameterException
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $actor = $request->getAttribute('actor');

        $include = $this->extractInclude($request);

        if($actor->exists) {
            $actor->load($include);
        }

        return $actor;
    }
}
