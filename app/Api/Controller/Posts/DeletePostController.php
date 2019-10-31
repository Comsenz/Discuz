<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: DeletePostController.php xxx 2019-10-31 20:38:00 LiuDongdong $
 */

namespace App\Api\Controller\Posts;

use App\Models\Post;
use Discuz\Api\Controller\AbstractDeleteController;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\EmptyResponse;

class DeletePostController extends AbstractDeleteController
{
    /**
     * @var Dispatcher
     */
    protected $bus;

    /**
     * @param Dispatcher $bus
     */
    public function __construct(Dispatcher $bus)
    {
        $this->bus = $bus;
    }

    /**
     * {@inheritdoc}
     */
    protected function delete(ServerRequestInterface $request)
    {
        // $this->bus->dispatch(
        //     new DeletePost(Arr::get($request->getQueryParams(), 'id'), $request->getAttribute('actor'))
        // );

        // TODO: $actor 权限验证 删除敏感词
        // $actor = $request->getAttribute('actor');
        // $this->assertCan($actor, 'deleteStopWord');

        $id = Arr::get($request->getQueryParams(), 'id');
        $ids = $id ? [$id] : $request->getParsedBody()->get('ids');

        if ($ids) {
            // 删除所有非首帖回复
            Post::whereIn('id', $ids)->where('is_first', false)->forceDelete();
        } else {
            throw (new ModelNotFoundException);
        }

        return new EmptyResponse(204);
    }
}
