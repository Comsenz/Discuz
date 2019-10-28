<?php
declare(strict_types=1);

namespace App\Api\Controller\Users;


use App\Api\Serializer\UserSerializer;
use App\Commands\Users\UpdateUser;
use Discuz\Api\Controller\AbstractDeleteController;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;
use Zend\Diactoros\Response\EmptyResponse;


class UpdateUsersController extends AbstractDeleteController
{
    public $serializer = UserSerializer::class;

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
        // TODO: Implement data() method.
        $actor = $request->getAttribute('actor');

        // 获取请求的参数
        $body = $request->getParsedBody();

        // 分发创建的任务
        $data = $this->bus->dispatch(
            new UpdateUser( $actor,$body->toArray())
        );
        return new EmptyResponse(204);
    }
}