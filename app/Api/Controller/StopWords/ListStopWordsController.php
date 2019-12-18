<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: ListStopWordsController.php xxx 2019-09-26 00:00:00 LiuDongdong $
 */

namespace App\Api\Controller\StopWords;

use App\Api\Serializer\StopWordSerializer;
use App\Repositories\StopWordRepository;
use Discuz\Api\Controller\AbstractListController;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Http\UrlGenerator;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class ListStopWordsController extends AbstractListController
{
    use AssertPermissionTrait;

    /**
     * {@inheritdoc}
     */
    public $serializer = StopWordSerializer::class;

    /**
     * {@inheritdoc}
     */
    public $include = ['user'];

    /**
     * @var StopWordRepository
     */
    protected $stopWords;

    /**
     * @var UrlGenerator
     */
    protected $url;

    /**
     * @param StopWordRepository $stopWords
     * @param UrlGenerator $url
     */
    public function __construct(StopWordRepository $stopWords, UrlGenerator $url)
    {
        $this->stopWords = $stopWords;
        $this->url = $url;
    }

    /**
     * {@inheritdoc}
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $this->assertAdmin($request->getAttribute('actor'));

        $limit = $this->extractLimit($request);
        $offset = $this->extractOffset($request);
        $include = $this->extractInclude($request);
        $keyword = Arr::get($this->extractFilter($request), 'q');

        $query = $this->stopWords
            ->query()
            ->with($include)
            ->when($keyword, function ($query, $keyword) {
                return $query->where('find', 'like', "%$keyword%");
            });

        $stopWordCount = $limit > 0 ? $query->count() : null;

        $stopWords = $query->limit($limit)->offset($offset)->get();

        $document->addPaginationLinks(
            $this->url->route('stop-words.index'),
            $request->getQueryParams(),
            $offset,
            $limit,
            $stopWordCount
        );

        $document->setMeta([
            'total' => $stopWordCount,
            'pageCount' => ceil($stopWordCount / $limit),
        ]);

        return $stopWords;
    }
}
