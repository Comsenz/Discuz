<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: BatchUpdateThreadController.php xxx 2019-10-21 14:08:00 LiuDongdong $
 */

namespace App\Api\Controller\Threads;

use App\Api\Serializer\ThreadSerializer;
use App\Commands\Thread\BatchUpdateThread;
use App\Models\User;
use Discuz\Api\Controller\AbstractCreateController;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;
use Zend\Diactoros\Response\EmptyResponse;

class BatchUpdateThreadController extends AbstractCreateController
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
        $actor = User::find(1);

        $ip = Arr::get($request->getServerParams(), 'REMOTE_ADDR', '127.0.0.1');

        $this->bus->dispatch(
            new BatchUpdateThread($actor, $request->getParsedBody(), $ip)
        );

        return new EmptyResponse(204);
    }
}
