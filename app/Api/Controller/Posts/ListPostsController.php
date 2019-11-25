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
use Illuminate\Database\Eloquent\Builder;
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
        // 'user.groups',
        // 'editedUser',
        // 'hiddenUser',
        'thread',
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
     * @var int|null
     */
    protected $postCount;

    /**
     * @param PostRepository $posts
     */
    public function __construct(PostRepository $posts)
    {
        $this->posts = $posts;
    }

    /**
     * {@inheritdoc}
     * @throws InvalidParameterException
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $actor = $request->getAttribute('actor');
        $limit = $this->extractLimit($request);
        $include = $this->extractInclude($request);

        $posts = $this->getPosts($request);

        $document->setMeta([
            'postCount' => $this->postCount,
            'pageCount' => ceil($this->postCount / $limit),
        ]);

        Post::setStateUser($actor);

        return $posts->load($include);
    }

    /**
     * @param ServerRequestInterface $request
     * @return array
     * @throws InvalidParameterException
     */
    private function getPosts(ServerRequestInterface $request)
    {
        $actor = $request->getAttribute('actor');
        $filter = $this->extractFilter($request);
        $sort = $this->extractSort($request);
        $limit = $this->extractLimit($request);
        $offset = $this->extractOffset($request);

        $query = $this->posts->query();

        $this->applyFilters($query, $filter, $actor);

        $this->postCount = $limit > 0 ? $query->count() : null;

        $query->skip($offset)->take($limit);

        foreach ((array) $sort as $field => $order) {
            $query->orderBy(Str::snake($field), $order);
        }

        return $query->get();
    }

    /**
     * @param Builder $query
     * @param array $filter
     * @param User $actor
     */
    private function applyFilters(Builder $query, array $filter, User $actor)
    {
        if ($userId = Arr::get($filter, 'user')) {
            $query->where('user_id', $userId);
        }

        if ($threadId = Arr::get($filter, 'thread')) {
            $query->where('thread_id', $threadId);
        }

        if ($replyId = Arr::get($filter, 'reply')) {
            $query->where('reply_id', $replyId);
        }

        // 待审核
        if ($isApproved = Arr::get($filter, 'isApproved')) {
            if ($isApproved == 'no' && $actor->can('review')) {
                $query->where('is_approved', false);
            } else {
                $query->where('is_approved', true);
            }
        }

        // 回收站
        if (($isDeleted = Arr::get($filter, 'isDeleted')) && $actor->can('viewTrashed')) {
            $query->when($isDeleted == 'yes', function ($query) {
                // 只看回收站帖子
                $query->onlyTrashed();
            }, function ($query) {
                // 包含回收站帖子
                $query->withTrashed();
            });
        }

        // event(new ConfigurePostsQuery($query, $filter));
    }
}
