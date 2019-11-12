<?php
declare(strict_types=1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: CreateInviteController.php 28830 2019-10-12 15:43 chenkeke $
 */

namespace App\Api\Controller\Invite;


use App\Api\Serializer\InviteSerializer;
use App\Commands\Invite\CreateInvite;
use Discuz\Api\Controller\AbstractCreateController;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class CreateInviteController extends AbstractCreateController
{
    /**
     * 返回的数据字段和格式.
     *
     * @var Serializer
     */
    public $serializer = InviteSerializer::class;

    /**
     * 数据操作.
     *
     * @param ServerRequestInterface $request  注入http请求对象
     * @param Document               $document 注入返回数据的文档
     * @return mixed
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        // 获取当前用户
        $actor = $request->getAttribute('actor');

        // 获取请求的参数
        $inputs = $request->getParsedBody();

        // 获取请求的IP
        $ipAddress = Arr::get($request->getServerParams(), 'REMOTE_ADDR', '127.0.0.1');

        // 分发创建圈子的任务
        $data = $this->bus->dispatch(
            new CreateInvite($actor, $inputs->toArray(), $ipAddress)
        );

        // 返回结果
        return $data;
    }
}
