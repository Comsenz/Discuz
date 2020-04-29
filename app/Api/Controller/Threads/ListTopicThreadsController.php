<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Threads;

use App\Models\Thread;
use App\Repositories\ThreadRepository;
use App\Repositories\UserRepository;
use Discuz\Auth\AssertPermissionTrait;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class ListTopicThreadsController extends ListThreadsController
{
    use AssertPermissionTrait;

    protected $users;

    public function __construct(ThreadRepository $threads, UrlGenerator $url, UserRepository $users)
    {
        parent::__construct($threads, $url);

        $this->users = $users;
    }

    /**
     * {@inheritdoc}
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $actor = $request->getAttribute('actor');

        $this->assertCan($actor, 'viewThreads');

        $limit = $this->extractLimit($request);
        $filter = $this->extractFilter($request);
        $offset = $this->extractOffset($request);
        $include = $this->extractInclude($request);
        $sort = $this->extractSort($request);

        $threads = $this->search($actor, $filter, $sort, $limit, $offset);

        $document->addPaginationLinks(
            $this->url->route('threads.topics'),
            $request->getQueryParams(),
            $offset,
            $limit,
            $this->threadCount
        );

        $document->setMeta([
            'threadCount' => $this->threadCount,
            'pageCount' => ceil($this->threadCount / $limit),
        ]);

        // 加载其他关联
        $threads->loadMissing($include);

        // 处理付费主题内容
        if (in_array('firstPost', $include) || in_array('threadVideo', $include)) {
            $threads = $this->cutThreadContent($threads, $actor, $include);
        }

        return $threads;
    }

    public function search($actor, $filter, $sort, $limit = null, $offset = 0)
    {
        $topic_id = Arr::get($filter, 'topic_id', '0');

        $query = $this->threads->query()
            ->select('threads.*')
            ->join('thread_topic', 'threads.id', '=', 'thread_topic.thread_id')
            ->where('threads.is_approved', Thread::APPROVED)
            ->where('thread_topic.topic_id', $topic_id)
            ->whereNull('threads.deleted_at');

        $this->threadCount = $limit > 0 ? $query->count() : null;

        $query->skip($offset)->take($limit);

        return $query->get();
    }
}
