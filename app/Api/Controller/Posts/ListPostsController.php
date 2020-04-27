<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
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
    public $optionalInclude = [
        'user.groups',
        'thread.category',
        'thread.firstPost',
        'lastThreeComments',
        'lastThreeComments.user',
        'lastThreeComments.replyUser',
        'deletedUser',
        'lastDeletedLog',
    ];

    /**
     * {@inheritdoc}
     */
    public $sortFields = [
        'id',
        'replyCount',
        'likeCount',
        'createdAt',
        'updatedAt',
        'deletedAt',
    ];

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
     * @var string
     */
    protected $tablePrefix;

    /**
     * @param PostRepository $posts
     * @param UrlGenerator $url
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function __construct(PostRepository $posts, UrlGenerator $url)
    {
        $this->posts = $posts;
        $this->url = $url;
        $this->tablePrefix = config('database.connections.mysql.prefix');
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
        $include = array_merge($this->extractInclude($request), ['likeState']);

        $posts = $this->search($actor, $filter, $sort, $limit, $offset);

        $document->addPaginationLinks(
            $this->url->route('posts.index'),
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

        // 特殊关联：最后三条点评
        if (in_array('lastThreeComments', $include)) {
            $posts = $this->loadLastThreeComments($posts);
        }

        // 特殊关联：最后一次删除的日志
        if (in_array('lastDeletedLog', $include)) {
            $posts->map(function ($post) {
                $log = $post->logs()
                    ->with('user')
                    ->where('action', 'hide')
                    ->orderBy('created_at', 'desc')
                    ->first();

                $post->setRelation('lastDeletedLog', $log);
            });
        }

        // 高亮敏感词
        if (Arr::get($filter, 'highlight') == 'yes') {
            $posts->load('stopWords');

            $posts->map(function ($post) {
                if ($post->stopWords) {
                    $stopWords = explode(',', $post->stopWords->stop_word);
                    $replaceWords = array_map(function ($word) {
                        return '<span class="highlight">' . $word . '</span>';
                    }, $stopWords);

                    $content = str_replace($stopWords, $replaceWords, $post->content);
                    $post->content = $content;
                }
            });
        }

        return $posts->loadMissing($include);
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

        // TODO: 搜索事件，给插件一个修改它的机会。
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
        $query->where('posts.is_comment', false);

        // 作者 ID
        if ($userId = Arr::get($filter, 'userId')) {
            $query->where('posts.user_id', $userId);
        }

        // 作者用户名
        if ($username = Arr::get($filter, 'username')) {
            $query->leftJoin('users as users1', 'users1.id', '=', 'posts.user_id')
                ->where('users1.username', 'like', "%{$username}%");
        }

        // 操作删除者 ID
        if ($deletedUserId = Arr::get($filter, 'deletedUserId')) {
            $query->where('posts.deleted_user_id', $deletedUserId);
        }

        // 操作删除者用户名
        if ($deletedUsername = Arr::get($filter, 'deletedUsername')) {
            $query->leftJoin('users as users2', 'users2.id', '=', 'posts.deleted_user_id')
                ->where('users2.username', 'like', "%{$deletedUsername}%");
        }

        // 发表于（开始时间）
        if ($createdAtBegin = Arr::get($filter, 'createdAtBegin')) {
            $query->where('posts.created_at', '>=', $createdAtBegin);
        }

        // 发表于（结束时间）
        if ($createdAtEnd = Arr::get($filter, 'createdAtEnd')) {
            $query->where('posts.created_at', '<=', $createdAtEnd);
        }

        // 删除于（开始时间）
        if ($deletedAtBegin = Arr::get($filter, 'deletedAtBegin')) {
            $query->where('posts.deleted_at', '>=', $deletedAtBegin);
        }

        // 删除于（结束时间）
        if ($deletedAtEnd = Arr::get($filter, 'deletedAtEnd')) {
            $query->where('posts.deleted_at', '<=', $deletedAtEnd);
        }

        // 分类
        if ($categoryId = Arr::get($filter, 'categoryId')) {
            $query->leftJoin('threads', 'threads.id', '=', 'posts.thread_id')
                ->where('threads.category_id', $categoryId);
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
        $isApproved = Arr::get($filter, 'isApproved');
        if ($isApproved === '1') {
            $query->where('posts.is_approved', Post::APPROVED);
        } elseif ($actor->can('approvePosts')) {
            if ($isApproved === '0') {
                $query->where('posts.is_approved', Post::UNAPPROVED);
            } elseif ($isApproved === '2') {
                $query->where('posts.is_approved', Post::IGNORED);
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

    /**
     * 特殊关联：最新三条点评
     *
     * @param Collection $posts
     * @return Collection
     */
    public function loadLastThreeComments(Collection $posts)
    {
        $postIds = $posts->pluck('id');

        $subSql = Post::query()
            ->selectRaw('count(*)')
            ->whereRaw($this->tablePrefix . 'a.`is_first` = `is_first`')
            ->whereRaw($this->tablePrefix . 'a.`is_comment` = `is_comment`')
            ->whereRaw($this->tablePrefix . 'a.`is_approved` = `is_approved`')
            ->whereRaw($this->tablePrefix . 'a.`reply_post_id` = `reply_post_id`')
            ->whereRaw($this->tablePrefix . 'a.`id` < `id`')
            ->toSql();

        $allLastThreeComments = Post::query()
            ->from('posts', 'a')
            ->whereRaw('(' . $subSql . ') < ?', [3])
            ->whereIn('reply_post_id', $postIds)
            ->whereNull('deleted_at')
            ->where('is_first', false)
            ->where('is_comment', true)
            ->where('is_approved', Post::APPROVED)
            ->orderBy('updated_at', 'desc')
            ->get()
            ->map(function (Post $post) {
                // 截取内容
                $post->content = Str::limit($post->content, 70);

                return $post;
            });

        $posts->map(function (Post $post) use ($allLastThreeComments) {
            $post->setRelation('lastThreeComments', $allLastThreeComments->where('reply_post_id', $post->id)->take(3));
        });

        return $posts;
    }
}
