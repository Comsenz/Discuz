<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: ListStopWordsController.php xxx 2019-09-26 00:00:00 LiuDongdong $
 */

namespace App\Api\Controller\StopWords;

use App\Api\Serializer\StopWordSerializer;
use App\Models\StopWord;
use Discuz\Api\Controller\AbstractListController;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class ListStopWordsController extends AbstractListController
{
    /**
     * {@inheritdoc}
     */
    public $serializer = StopWordSerializer::class;

    /**
     * {@inheritdoc}
     */
    public function data(ServerRequestInterface $request, Document $document)
    {
        // TODO: $actor 权限验证 查询敏感词
        // $actor = $request->getAttribute('actor');
        // $this->assertCan($actor, 'viewStopWordList');

        $limit = Arr::get($request->getQueryParams(), 'limit', 10);
        $offset = Arr::get($request->getQueryParams(), 'offset');
        $keyword = Arr::get($request->getQueryParams(), 'keyword');

        $results = StopWord::when($keyword, function ($query, $keyword) {
            return $query->where('find', 'like', "%$keyword%");
        })->limit($limit)->offset($offset);

        $document->addPaginationLinks(
            // $this->url->to('api')->route('discussions.index'),
            'stop-words.index',
            $request->getQueryParams(),
            $offset,
            $limit,
            // $results->areMoreResults() ? null : 0
            $results->count()
        );

        return $results->get();
    }
}
