<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Threads;

use App\Api\Serializer\ThreadSerializer;
use App\Models\Order;
use App\Models\Post;
use App\Models\Thread;
use App\Repositories\PostRepository;
use App\Repositories\ThreadRepository;
use Discuz\Api\Controller\AbstractResourceController;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Auth\Exception\PermissionDeniedException;
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
     * @throws PermissionDeniedException
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $threadId = Arr::get($request->getQueryParams(), 'id');
        $actor = $request->getAttribute('actor');
        $include = $this->extractInclude($request);

        // 主题
        $thread = $this->thread->findOrFail($threadId, $actor);

        $this->assertCan($actor, 'viewPosts', $thread);

        // 付费主题对未付费用户只展示部分内容
        if ($thread->price > 0 && (in_array('firstPost', $include) || in_array('threadVideo', $include))) {
            // 是否付费
            if ($thread->user_id == $actor->id || $actor->isAdmin()) {
                $paid = true;
            } else {
                $paid = Order::where('user_id', $actor->id)
                    ->where('thread_id', $thread->id)
                    ->where('status', Order::ORDER_STATUS_PAID)
                    ->where('type', Order::ORDER_TYPE_THREAD)
                    ->exists();
            }

            $thread->setAttribute('paid', $paid);

            // 截取内容、隐藏图片及附件
            if (in_array('firstPost', $include) && !$paid) {
                // $thread->firstPost->content = Str::limit($thread->firstPost->content, Post::SUMMARY_LENGTH);
                $thread->firstPost->content = '';
                $thread->firstPost->setRelation('images', collect());
                $thread->firstPost->setRelation('attachments', collect());
            }

            // 付费视频，未付费时，隐藏视频
            if (in_array('threadVideo', $include) && $thread->type == 2 && !$paid) {
                $thread->threadVideo && $thread->threadVideo->file_id = '';
            }
        }

        // 更新浏览量
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
                ->where('status', Order::ORDER_STATUS_PAID)
                ->where('type', Order::ORDER_TYPE_REWARD)
                ->orderBy('created_at', 'desc')
                ->orderBy('id', 'desc')
                ->get();

            $thread->setRelation('rewardedUsers', $allRewardedUser->pluck('user')->filter());
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
