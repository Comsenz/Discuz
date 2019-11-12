<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: ListThreadsController.php xxx 2019-10-09 20:08:00 LiuDongdong $
 */

namespace App\Api\Controller\Threads;

use App\Api\Serializer\ThreadSerializer;
use App\Models\Thread;
use App\Repositories\ThreadRepository;
use Discuz\Api\Controller\AbstractListController;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
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
    public $include = [
        'user',
        'firstPost',
    ];

    /**
     * {@inheritdoc}
     */
    public $optionalInclude = [
        'lastThreePosts',
        'lastThreePosts.user',
    ];

    /**
     * {@inheritdoc}
     */
    public $sortFields = [
        'postCount',
        'createdAt',
        'updatedAt'
    ];

    /**
     * {@inheritdoc}
     */
    protected $defaultSort = [
        'updatedAt' => 'desc'
    ];

    /**
     * @var ThreadRepository
     */
    protected $threads;

    /**
     * @var UrlGenerator
     */
    protected $url;

    /**
     * @var bool
     */
    protected $hasMoreResults;

    /**
     * @param ThreadRepository $threads
     * @param UrlGenerator $url
     */
    public function __construct(ThreadRepository $threads, UrlGenerator $url)
    {
        $this->threads = $threads;
        $this->url = $url;
    }

    /**
     * {@inheritdoc}
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $actor = $request->getAttribute('actor');
        $query = Arr::get($this->extractFilter($request), 'q');
        $sort = $this->extractSort($request) ?: $this->defaultSort;

        // $criteria = new SearchCriteria($actor, $query, $sort);

        $limit = $this->extractLimit($request);
        $offset = $this->extractOffset($request);
        // $load = array_merge($this->extractInclude($request), ['state']);
        $include = $this->extractInclude($request);

        // 查主题
        // $results = $this->searcher->search($criteria, $limit, $offset);
        $threads = $this->search($actor, $query, $sort, $limit, $offset);

        $document->addPaginationLinks(
            $this->url->route('threads.index'),
            $request->getQueryParams(),
            $offset,
            $limit,
            $this->hasMoreResults ? null : 0
        );

        // Discussion::setStateUser($actor);

        $threads = $threads->load(array_diff($include, ['lastThreePosts', 'lastThreePosts.user']));

        $threads = $threads->each(function ($thread) {
            $thread->setRelation('lastThreePosts', $thread->lastThreePosts());
        });

        return $threads;
    }

    /**
     * @param $actor
     * @param $queryWord
     * @param $sort
     * @param int|null $limit
     * @param int $offset
     *
     * @return Thread
     */
    public function search($actor, $queryWord, $sort, $limit = null, $offset = 0)
    {
        $query = $this->threads->query()->select('threads.*')->whereVisibleTo($actor);

        // 关键词搜索
        $query->when($queryWord, function ($query, $queryWord) {
            $query->leftJoin('posts', 'threads.id', '=', 'posts.thread_id')
                ->where('content', 'like', "%{$queryWord}%")
                ->where('is_first', true);
        });

        // TODO: 关键词搜索 优化搜索
        // if ($queryWord) {
        //     $subQuery = Post::whereVisibleTo($actor)
        //         ->select('posts.thread_id')
        //         ->where('content', 'like', "%{$queryWord}%")
        //         ->where('is_first', true);
        //
        //     $query->leftJoinSub($subQuery, 'posts', function ($join) {
        //         $join->on('threads.id', '=', 'posts.thread_id');
        //     });
        // }

        $query->skip($offset)->take($limit + 1);

        foreach ((array) $sort as $field => $order) {
            $query->orderBy(Str::snake($field), $order);
        }

        // 搜索事件，给插件一个修改它的机会。
        // $this->events->dispatch(new Searching($search, $criteria));

        $threads = $query->get();

        $this->hasMoreResults = $limit > 0 && $threads->count() > $limit;

        if ($this->hasMoreResults) {
            $threads->pop();
        }

        return $threads;
    }
}
