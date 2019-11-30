<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: DeleteCategoryController.php xxx 2019-11-30 11:16:00 LiuDongdong $
 */

namespace App\Api\Controller\Category;

use App\Commands\Category\DeleteCategory;
use Discuz\Api\Controller\AbstractDeleteController;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;

class DeleteCategoryController extends AbstractDeleteController
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
        $ids = explode(',', Arr::get($request->getQueryParams(), 'id'));
        $actor = $request->getAttribute('actor');

        foreach ($ids as $id) {
            $this->bus->dispatch(
                new DeleteCategory($id, $actor)
            );
        }
    }
}
