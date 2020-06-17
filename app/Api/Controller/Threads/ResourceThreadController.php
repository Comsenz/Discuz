<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Threads;

use App\Api\Serializer\ThreadSerializer;
use App\Models\Attachment;
use App\Models\Order;
use App\Models\Post;
use App\Models\Thread;
use App\Repositories\PostRepository;
use App\Repositories\ThreadRepository;
use Discuz\Api\Controller\AbstractResourceController;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Auth\Exception\PermissionDeniedException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;
use Tobscure\JsonApi\Exception\InvalidParameterException;

class ResourceThreadController extends AbstractResourceController
{
    use AssertPermissionTrait;

    /**
     * @var ThreadRepository
     */
    protected $threads;

    /**
     * @var PostRepository
     */
    protected $posts;

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
        'threadVideo',
        'firstPost.images',
        'firstPost.attachments',
        'posts',
        'posts.user',
        'posts.replyUser',
        'posts.thread',
        'posts.images',
    ];

    /**
     * {@inheritdoc}
     */
    public $optionalInclude = [
        'user.groups',
        'category',
        'firstPost.likedUsers',
        'posts.likedUsers',
        'rewardedUsers',
        'paidUsers',
        'posts.mentionUsers',
        'firstPost.mentionUsers',
        'topic',
    ];

    /**
     * @param ThreadRepository $threads
     * @param PostRepository $posts
     */
    public function __construct(ThreadRepository $threads, PostRepository $posts)
    {
        $this->threads = $threads;
        $this->posts = $posts;
    }

    /**
     * {@inheritdoc}
     * @throws InvalidParameterException
     * @throws PermissionDeniedException
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $threadId = Arr::get($request->getQueryParams(), 'id');
        $actor = $request->getAttribute('actor');
        $include = $this->extractInclude($request);

        $thread = $this->threads->findOrFail($threadId, $actor);

        $this->assertCan($actor, 'viewPosts', $thread);

        // 更新浏览量
        $thread->timestamps = false;
        $thread->increment('view_count');

        // 帖子及其关联模型
        if (($postRelationships = $this->getPostRelationships($include)) || in_array('posts', $include)) {
            $this->includePosts($thread, $request, $postRelationships);
        }

        // 特殊关联：打赏的人
        if (in_array('rewardedUsers', $include)) {
            $this->loadOrderUsers($thread, Order::ORDER_TYPE_REWARD);
        }

        // 特殊关联：付费用户
        if (in_array('paidUsers', $include)) {
            $this->loadOrderUsers($thread, Order::ORDER_TYPE_THREAD);
        }

        // 主题关联模型
        $thread->loadMissing($include);

        return $thread;
    }

    /**
     * @param Thread $thread
     * @param ServerRequestInterface $request
     * @param array $include
     * @throws InvalidParameterException
     */
    private function includePosts(Thread $thread, ServerRequestInterface $request, array $include)
    {
        $actor = $request->getAttribute('actor');
        $limit = $this->extractLimit($request);
        $offset = $this->extractOffset($request);

        $isDeleted = Arr::get($this->extractFilter($request), 'isDeleted');

        $posts = $thread->posts()
            ->whereVisibleTo($actor)
            ->when($isDeleted, function (Builder $query, $isDeleted) use ($actor) {
                if ($isDeleted == 'yes' && $actor->hasPermission('viewTrashed')) {
                    // 只看回收站帖子
                    $query->whereNotNull('posts.deleted_at');
                } elseif ($isDeleted == 'no') {
                    // 不看回收站帖子
                    $query->whereNull('posts.deleted_at');
                }
            })
            ->where('is_first', false)
            ->orderBy('created_at')
            ->skip($offset)
            ->take($limit)
            ->with($include)
            ->get()
            ->each(function (Post $post) use ($thread) {
                $post->thread = $thread;
            });

        $thread->setRelation('posts', $posts);
    }

    /**
     * @param array $include
     * @return array
     */
    private function getPostRelationships(array $include)
    {
        $prefixLength = strlen($prefix = 'posts.');
        $relationships = [];

        foreach ($include as $relationship) {
            if (substr($relationship, 0, $prefixLength) === $prefix) {
                $relationships[] = substr($relationship, $prefixLength);
            }
        }

        return $relationships;
    }

    /**
     * @param $thread
     * @param $type
     * @return Thread
     */
    private function loadOrderUsers(Thread $thread, $type)
    {
        switch ($type) {
            case Order::ORDER_TYPE_REWARD:
                $relation = 'rewardedUsers';
                break;
            case Order::ORDER_TYPE_THREAD:
                $relation = 'paidUsers';
                break;
            default:
                return $thread;
        }

        $orderUsers = Order::with('user')
            ->where('thread_id', $thread->id)
            ->where('status', Order::ORDER_STATUS_PAID)
            ->where('type', $type)
            ->where('is_anonymous', false)
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc')
            ->get();

        return $thread->setRelation($relation, $orderUsers->pluck('user')->filter());
    }
}
