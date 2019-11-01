<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: ListPostsController.php xxx 2019-10-30 20:28:00 LiuDongdong $
 */

namespace App\Api\Controller\Posts;

use App\Api\Serializer\PostSerializer;
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
        // 'thread'
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
        $include = $this->extractInclude($request);

        $posts = $this->getPosts($request);

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

        // $this->assertCan($actor, 'viewTrashedPost');

        $this->applyFilters($query, $filter);

        $query->skip($offset)->take($limit);

        foreach ((array) $sort as $field => $order) {
            $query->orderBy(Str::snake($field), $order);
        }

        return $query->get();
    }

    /**
     * @param Builder $query
     * @param array $filter
     */
    private function applyFilters(Builder $query, array $filter)
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

        // event(new ConfigurePostsQuery($query, $filter));
    }
}
