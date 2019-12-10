<?php
declare(strict_types=1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: UpdateInviteController.php 28830 2019-10-12 15:47 yanchen $
 */

namespace App\Api\Controller\Invite;


use App\Api\Serializer\InviteSerializer;
use App\Commands\Invite\UpdateInvite;
use Discuz\Api\Controller\AbstractCreateController;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class UpdateInviteController extends AbstractCreateController
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
        $id = Arr::get($request->getQueryParams(), 'id');
        $actor = $request->getAttribute('actor');
        $data = $request->getParsedBody()->get('data', []);

        // 分发创建站点的任务
        $data = $this->bus->dispatch(
            new UpdateInvite()
        );

        // 返回结果
        return $data;
    }
}
