<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: CreatePostController.php xxx 2019-10-18 10:41:00 LiuDongdong $
 */

namespace App\Api\Controller\Posts;

use App\Api\Serializer\PostSerializer;
use App\Commands\Post\CreatePost;
use Discuz\Api\Controller\AbstractCreateController;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class CreatePostController extends AbstractCreateController
{
    /**
     * {@inheritdoc}
     */
    public $serializer = PostSerializer::class;

    /**
     * {@inheritdoc}
     */
    public function data(ServerRequestInterface $request, Document $document)
    {
        // TODO: $actor 权限验证 用户模型
        // $actor = $request->getAttribute('actor');
        // $this->assertCan($actor, 'createWordList');
        $actor = new \stdClass();
        $actor->id = 2;

        $threadId = $request->getParsedBody()->get('threadId');
        $ip = Arr::get($request->getServerParams(), 'REMOTE_ADDR', '127.0.0.1');

        // 检查发帖频率
        // if (! $request->getAttribute('bypassFloodgate')) {
        //     $this->floodgate->assertNotFlooding($actor);
        // }

        return $this->bus->dispatch(
            new CreatePost($threadId, $actor, $request->getParsedBody(), $ip)
        );
    }
}
