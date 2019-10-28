<?php
declare(strict_types=1);

namespace App\Api\Controller\Users;


use App\Commands\Users\DeleteUsers;
use Discuz\Api\Controller\AbstractDeleteController;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;
use Zend\Diactoros\Response\EmptyResponse;


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
        // 获取当前用户
        $actor = $request->getAttribute('actor');

        // 获取请求的参数
        $inputs = $request->getParsedBody();

        $this->bus->dispatch(
            new DeleteUsers($actor, $inputs->toArray())
        );

        return new EmptyResponse(204);

    }
}