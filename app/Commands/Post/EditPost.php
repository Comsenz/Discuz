<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: EditPost.php xxx 2019-10-24 15:10:00 LiuDongdong $
 */

namespace App\Commands\Post;

use App\Events\Post\PostWasApproved;
use App\Events\Post\Saving;
use App\Models\Post;
use App\Models\User;
use App\Repositories\PostRepository;
use App\Validators\PostValidator;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Auth\Exception\PermissionDeniedException;
use Discuz\Foundation\EventsDispatchTrait;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;

class EditPost
{
    use AssertPermissionTrait;
    use EventsDispatchTrait;

    /**
     * The ID of the post to edit.
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
     * The attributes to update on the post.
     *
     * @var array
     */
    public $data;

    /**
     * @param int $postId The ID of the post to edit.
     * @param User $actor The user performing the action.
     * @param array $data The attributes to update on the post.
     */
    public function __construct($postId, User $actor, array $data)
    {
        $this->postId = $postId;
        $this->actor = $actor;
        $this->data = $data;
    }

    /**
     * @param Dispatcher $events
     * @param PostRepository $posts
     * @param PostValidator $validator
     * @return Post
     * @throws PermissionDeniedException
     * @throws ValidationException
     */
    public function handle(Dispatcher $events, PostRepository $posts, PostValidator $validator)
    {
        $this->events = $events;

        $post = $posts->findOrFail($this->postId, $this->actor);

        $attributes = Arr::get($this->data, 'attributes', []);

        if (isset($attributes['content'])) {
            $this->assertCan($this->actor, 'edit', $post);

            $post->revise($attributes['content'], $this->actor);
        } else {
            // 不修改内容时，不更新修改时间
            $post->timestamps = false;
        }

        if (isset($attributes['isApproved'])) {
            $this->assertCan($this->actor, 'approve', $post);

            $post->is_approved = $attributes['isApproved'];

            $post->raise(new PostWasApproved(
                $post,
                $this->actor,
                ['message' => isset($attributes['message']) ? $attributes['message'] : '']
            ));
        }

        if (isset($attributes['isDeleted'])) {
            $this->assertCan($this->actor, 'delete', $post);

            $message = isset($attributes['message']) ? $attributes['message'] : '';

            if ($attributes['isDeleted']) {
                $post->hide($this->actor, $message);
            } else {
                $post->restore($this->actor, $message);
            }
        }

        $this->events->dispatch(
            new Saving($post, $this->actor, $this->data)
        );

        $validator->valid($post->getDirty());

        $post->save();

        $this->dispatchEventsFor($post, $this->actor);

        return $post;
    }
}
