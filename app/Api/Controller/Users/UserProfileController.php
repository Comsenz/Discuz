<?php
declare(strict_types=1);

namespace App\Api\Controller\Users;


use App\Api\Serializer\UserPorfileSerializer;
use App\Commands\UserProfile\UserProfile;
use Discuz\Api\Controller\AbstractResourceController;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class UserProfileController extends AbstractResourceController
{
    public $serializer = UserPorfileSerializer::class;

    public function data(ServerRequestInterface $request, Document $document)
    {
        // TODO: Implement data() method.
        // 获取当前用户
        $actor = $request->getAttribute('actor');

        // 获取请求的参数
        $body = $request->getQueryParams();

        // 分发创建的任务
        $data = $this->bus->dispatch(
            new UserProfile($body['id'], $actor)
        );

        // 返回结果
        return $data;
    }
}
