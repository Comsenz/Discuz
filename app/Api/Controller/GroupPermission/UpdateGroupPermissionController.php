<?php
declare(strict_types=1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: UpdateGroupPermissionController.php 28830 2019-10-23 11:06 chenkeke $
 */

namespace App\Api\Controller\GroupPermission;


use App\Api\Serializer\GroupPermissionSerializer;
use App\Commands\GroupPermission\UpdateGroupPermission;
use Discuz\Api\Controller\AbstractListController;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;
use Zend\Diactoros\Response\EmptyResponse;

class UpdateGroupPermissionController extends AbstractListController
{

    public $serializer = GroupPermissionSerializer::class;

    /**
     * Get the data to be serialized and assigned to the response document.
     *
     * @param ServerRequestInterface $request
     * @param Document $document
     * @return mixed
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        // 获取当前用户
        $actor = $request->getAttribute('actor');

        // 获取请求的参数
        $body = $request->getQueryParams();
        $inputs = $request->getParsedBody();

        return $this->bus->dispatch(
            new UpdateGroupPermission($body['id'], $actor, $inputs->toArray())
        );
    }
}
