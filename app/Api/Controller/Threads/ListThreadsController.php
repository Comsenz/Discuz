<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: ListThreadsController.php xxx 2019-10-09 20:08:00 LiuDongdong $
 */

namespace App\Api\Controller\Threads;

use App\Api\Serializer\ThreadSerializer;
use App\Repositories\ThreadRepository;
use App\Searchs\Thread\ThreadSearch;
use Discuz\Api\Controller\AbstractListController;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class ListThreadsController extends AbstractListController
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
        return $this->searcher->apply(
            new ThreadSearch(
                $request->getAttribute('actor'),
                $request->getQueryParams(),
                ThreadRepository::query()
            )
        )->search()->getMultiple();
    }
}
