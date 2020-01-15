<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Commands\Post;

use App\Events\Post\Deleted;
use App\Events\Post\Deleting;
use App\Models\Post;
use App\Models\User;
use App\Repositories\PostRepository;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Auth\Exception\PermissionDeniedException;
use Discuz\Foundation\EventsDispatchTrait;
use Illuminate\Contracts\Events\Dispatcher;

class DeletePost
{
    use AssertPermissionTrait;
    use EventsDispatchTrait;

    /**
     * The ID of the post to delete.
     *
     * @var int
     */
    public $postId;

    /**
     * The user performing the action.
     *
     * @var User
     */
    public $actor;

    /**
     * 暂未用到，留给插件使用
     *
     * @var array
     */
    public $data;

    /**
     * @param int $postId
     * @param User $actor
     * @param array $data
     */
    public function __construct($postId, User $actor, array $data = [])
    {
        $this->postId = $postId;
        $this->actor = $actor;
        $this->data = $data;
    }

    /**
     * @param Dispatcher $events
     * @param PostRepository $posts
     * @return Post
     * @throws PermissionDeniedException
     */
    public function handle(Dispatcher $events, PostRepository $posts)
    {
        $this->events = $events;

        $post = $posts->findOrFail($this->postId, $this->actor);

        $this->assertCan($this->actor, 'delete', $post);

        $this->events->dispatch(
            new Deleting($post, $this->actor, $this->data)
        );

        $post->raise(new Deleted($post));
        $post->delete();

        $this->dispatchEventsFor($post, $this->actor);

        return $post;
    }
}
