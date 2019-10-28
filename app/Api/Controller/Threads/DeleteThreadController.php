<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: DeleteThreadController.php xxx 2019-10-21 14:08:00 LiuDongdong $
 */

namespace App\Api\Controller\Threads;

use App\Models\Post;
use App\Models\Thread;
use Discuz\Api\Controller\AbstractDeleteController;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;
use Zend\Diactoros\Response\EmptyResponse;

class DeleteThreadController extends AbstractDeleteController
{
    /**
     * {@inheritdoc}
     */
    public function delete(ServerRequestInterface $request)
    {
        // TODO: $actor 权限验证 删除敏感词
        // $actor = $request->getAttribute('actor');
        // $this->assertCan($actor, 'deleteStopWord');

        $id = Arr::get($request->getQueryParams(), 'id');
        $ids = $id ? [$id] : $request->getParsedBody()->get('ids');

        if ($ids) {
            // 删除相关主题下的所有回复
            Post::whereIn('thread_id', $ids)->forceDelete();

            // 删除主题
            Thread::whereIn('id', $ids)->forceDelete();
        } else {
            throw (new ModelNotFoundException);
        }

        return new EmptyResponse(204);
    }

    /**
     * {@inheritdoc}
     */
    public function data(ServerRequestInterface $request, Document $document)
    {
        // TODO: Implement data() method.
    }
}
