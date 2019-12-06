<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: ResourceThreadController.php xxx 2019-10-10 10:56:00 LiuDongdong $
 */

namespace App\Api\Controller\Threads;

use App\Api\Serializer\ThreadSerializer;
use App\Models\Order;
use App\Models\Thread;
use App\Repositories\PostRepository;
use App\Repositories\ThreadRepository;
use Discuz\Api\Controller\AbstractResourceController;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;
use Tobscure\JsonApi\Exception\InvalidParameterException;

class ResourceThreadController extends AbstractResourceController
{
    /**
     * @var ThreadRepository
     */
    protected $thread;

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
        'firstPost.images',
        'firstPost.attachments',
        'posts',
        'posts.user',
        'posts.thread',
        'posts.images',
    ];

    /**
     * {@inheritdoc}
     */
    public $optionalInclude = [
        'category',
        'firstPost.likedUsers',
        'posts.likedUsers',
        'rewardedUsers',
    ];

    /**
     * @param ThreadRepository $thread
     * @param PostRepository $posts
     */
    public function __construct(ThreadRepository $thread, PostRepository $posts)
    {
        $this->thread = $thread;
        $this->posts = $posts;
    }

    /**
     * {@inheritdoc}
     * @throws InvalidParameterException
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $threadId = Arr::get($request->getQueryParams(), 'id');
        $actor = $request->getAttribute('actor');
        $include = $this->extractInclude($request);

        // 主题
        $thread = $this->thread->findOrFail($threadId, $actor);
        $thread->timestamps = false;
        $thread->increment('view_count');

        // 帖子及其关联模型
        if (in_array('posts', $include)) {
            $postRelationships = $this->getPostRelationships($include);

            $this->includePosts($thread, $request, $postRelationships);
        }

        // 打赏的用户
        if (in_array('rewardedUsers', $include)) {
            $allRewardedUser = Order::with('user')
                ->where('thread_id', $thread->id)
                ->where('type', 2)
                ->where('status', 1)
                ->get();

            $thread->setRelation('rewardedUsers', $allRewardedUser->pluck('user'));
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
            ->when($isDeleted, function ($query, $isDeleted) use ($actor) {
                if ($isDeleted == 'yes' && $actor->can('viewTrashed')) {
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
            ->each(function ($post) use ($thread) {
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
}
