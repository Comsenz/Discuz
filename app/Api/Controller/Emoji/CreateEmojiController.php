<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Invite;

use App\Api\Serializer\InviteSerializer;
use Discuz\Api\Controller\AbstractCreateController;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;
use Illuminate\Contracts\Bus\Dispatcher;

class CreateEmojiController extends AbstractCreateController
{
    /**
     * 返回的数据字段和格式.
     *
     * @var Serializer
     */
    public $serializer = InviteSerializer::class;

    /**
     * @param Dispatcher $bus
     */
    public function __construct(Dispatcher $bus)
    {
        $this->bus = $bus;
    }

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

        // 分发创建站点的任务
        $data = $this->bus->dispatch(
            new CreateC($actor, $request->getParsedBody()->get('data', []))
        );

        // 返回结果
        return $data;
    }
}
