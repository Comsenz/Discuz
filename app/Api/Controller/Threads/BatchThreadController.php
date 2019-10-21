<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: BatchThreadController.php xxx 2019-10-21 14:08:00 LiuDongdong $
 */

namespace App\Api\Controller\Threads;

use App\Api\Serializer\ThreadSerializer;
use App\Commands\Thread\BatchDeleteThread;
use App\Commands\Thread\BatchUpdateThread;
use Discuz\Api\Controller\AbstractCreateController;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class BatchThreadController extends AbstractCreateController
{
    /**
     * {@inheritdoc}
     */
    public $serializer = ThreadSerializer::class;

    /**
     * {@inheritdoc}
     */
    public function data(ServerRequestInterface $request, Document $document)
    {
        // TODO: $actor 权限验证 用户模型
        // $actor = $request->getAttribute('actor');
        $actor = new \stdClass();
        $actor->id = 1;

        $ip = Arr::get($request->getServerParams(), 'REMOTE_ADDR', '127.0.0.1');

        // 批量删除（物理删除）
        if ($request->getParsedBody()->get('delete')) {
            $this->bus->dispatch(
                new BatchDeleteThread($actor, collect($request->getParsedBody()->get('delete')), $ip)
            );
        }

        // 批量修改（包含软删除）
        if ($request->getParsedBody()->get('update')) {
            $this->bus->dispatch(
                new BatchUpdateThread($actor, collect($request->getParsedBody()->get('update')), $ip)
            );
        }
    }
}
