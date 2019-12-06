<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: UpdateCategoryController.php xxx 2019-11-29 17:59:00 LiuDongdong $
 */

namespace App\Api\Controller\Category;

use App\Api\Serializer\CategorySerializer;
use App\Commands\Category\EditCategory;
use Discuz\Api\Controller\AbstractCreateController;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class UpdateCategoryController extends AbstractCreateController
{
    /**
     * {@inheritdoc}
     */
    public $serializer = CategorySerializer::class;

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
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $actor = $request->getAttribute('actor');
        $categoryId = Arr::get($request->getQueryParams(), 'id');
        $data = $request->getParsedBody()->get('data', []);
        $ip = Arr::get($request->getServerParams(), 'REMOTE_ADDR', '127.0.0.1');

        return $this->bus->dispatch(
            new EditCategory($categoryId, $actor, $data, $ip)
        );
    }
}
