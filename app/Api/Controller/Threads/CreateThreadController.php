<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: CreateThreadController.php xxx 2019-10-10 11:08:00 LiuDongdong $
 */

namespace App\Api\Controller\Threads;

use App\Api\Serializer\ThreadSerializer;
use App\Commands\Thread\CreateThread;
use Discuz\Api\Controller\AbstractCreateController;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class CreateThreadController extends AbstractCreateController
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
        // $this->assertCan($actor, 'createWordList');
        $actor = new \stdClass();
        $actor->id = 1;

        $ip = Arr::get($request->getServerParams(), 'REMOTE_ADDR', '127.0.0.1');

        // 检查发帖频率
        // if (! $request->getAttribute('bypassFloodgate')) {
        //     $this->floodgate->assertNotFlooding($actor);
        // }

        return $this->bus->dispatch(
            new CreateThread($actor, $request->getParsedBody(), $ip)
        );
    }
}
