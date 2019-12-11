<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: ListPostsController.php xxx 2019-10-30 20:28:00 LiuDongdong $
 */

namespace App\Api\Controller\Posts;

use App\Api\Serializer\PostSerializer;
use App\Models\Post;
use App\Models\User;
use App\Repositories\PostRepository;
use Discuz\Api\Controller\AbstractListController;
use Discuz\Auth\AssertPermissionTrait;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;
use Tobscure\JsonApi\Exception\InvalidParameterException;

class ListPostsController extends AbstractListController
{
    use AssertPermissionTrait;

    /**
     * {@inheritdoc}
     */
    public $serializer = PostSerializer::class;

    /**
     * {@inheritdoc}
     */
    public $include = [
        'user',
        'replyUser',
        'thread',
        'images',
    ];

    /**
     * {@inheritdoc}
     */
    public $sortFields = ['createdAt'];

    /**
     * @var PostRepository
     */
    protected $posts;

    /**
     * @var UrlGenerator
     */
    protected $url;

    /**
     * @var int|null
     */
    protected $postCount;

    /**
     * @param PostRepository $posts
     * @param UrlGenerator $url
     */
    public function __construct(PostRepository $posts, UrlGenerator $url)
    {
        $this->posts = $posts;
        $this->url = $url;
    }

    /**
     * {@inheritdoc}
     * @throws InvalidParameterException
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $actor = $request->getAttribute('actor');
        $filter = $this->extractFilter($request);
        $sort = $this->extractSort($request);

        $limit = $this->extractLimit($request);
        $offset = $this->extractOffset($request);
        $load = array_merge($this->extractInclude($request), ['likeState']);

        $posts = $this->search($actor, $filter, $sort, $limit, $offset);

        $document->addPaginationLinks(
            $this->url->route('threads.index'),
            $request->getQueryParams(),
            $offset,
            $limit,
            $this->postCount
        );

        $document->setMeta([
            'postCount' => $this->postCount,
            'pageCount' => ceil($this->postCount / $limit),
        ]);

        Post::setStateUser($actor);

        return $posts->load($load);
    }

    /**
     * @param $actor
     * @param $filter
     * @param $sort
     * @param int|null $limit
     * @param int $offset
     *
     * @return Collection
     */
    private function search($actor, $filter, $sort, $limit = null, $offset = 0)
    {
        $query = $this->posts->query()->select('posts.*')->whereVisibleTo($actor);

        $this->applyFilters($query, $filter, $actor);

        $this->postCount = $limit > 0 ? $query->count() : null;

        $query->skip($offset)->take($limit);

        foreach ((array) $sort as $field => $order) {
            $query->orderBy(Str::snake($field), $order);
        }

        // 搜索事件，给插件一个修改它的机会。
        // $this->events->dispatch(new Searching($search, $criteria));

        return $query->get();
    }

    /**
     * @param Builder $query
     * @param array $filter
     * @param User $actor
     */
    private function applyFilters(Builder $query, array $filter, User $actor)
    {
        $query->where('posts.is_first', false);

        // 作者
        if ($userId = Arr::get($filter, 'user')) {
            $query->where('posts.user_id', $userId);
        }

        // 主题
        if ($threadId = Arr::get($filter, 'thread')) {
            $query->where('posts.thread_id', $threadId);
        }

        // 回复
        if ($replyId = Arr::get($filter, 'reply')) {
            $query->where('posts.reply_post_id', $replyId);
        }

        // 待审核
        if ($isApproved = Arr::get($filter, 'isApproved')) {
            if ($isApproved == 'yes') {
                $query->where('threads.is_approved', Post::APPROVED);
            } elseif ($actor->can('approvePosts')) {
                if ($isApproved == 'no') {
                    $query->where('posts.is_approved', Post::UNAPPROVED);
                } elseif ($isApproved == 'ignore') {
                    $query->where('posts.is_approved', Post::IGNORED);
                }
            }
        }

        // 回收站
        if ($isDeleted = Arr::get($filter, 'isDeleted')) {
            if ($isDeleted == 'yes' && $actor->can('viewTrashed')) {
                // 只看回收站帖子
                $query->whereNotNull('posts.deleted_at');
            } elseif ($isDeleted == 'no') {
                // 不看回收站帖子
                $query->whereNull('posts.deleted_at');
            }
        }

        // 关键词搜索
        $queryWord = Arr::get($filter, 'q');
        $query->when($queryWord, function ($query, $queryWord) {
            $query->where('content', 'like', "%{$queryWord}%")->where('is_first', false);
        });

        // event(new ConfigurePostsQuery($query, $filter));
    }
}
