<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: EditPost.php xxx 2019-10-24 15:10:00 LiuDongdong $
 */

namespace App\Commands\Post;

use App\Events\Post\Saving;
use App\Models\Post;
use App\Models\User;
use App\Repositories\PostRepository;
use App\Validators\PostValidator;
use Discuz\Foundation\EventsDispatchTrait;
use Illuminate\Contracts\Events\Dispatcher as EventDispatcher;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;

class EditPost
{
    use EventsDispatchTrait;
    // use AssertPermissionTrait;

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
     * @param EventDispatcher $events
     * @param PostRepository $posts
     * @param PostValidator $validator
     * @return Post
     * @throws ValidationException
     */
    public function handle(EventDispatcher $events, PostRepository $posts, PostValidator $validator)
    {
        $this->events = $events;

        // TODO: 权限验证（是否有权查看）
        // $this->assertCan($this->actor, 'startDiscussion');

        $attributes = Arr::get($this->data, 'attributes', []);

        // 数据验证
        $validator->valid($this->data);

        $post = $posts->findOrFail($this->postId, $this->actor);

        if (isset($attributes['content'])) {
            // TODO: 是否有权修改内容
            // $this->assertCan($actor, 'edit', $post);

            $post->content = $attributes['content'];
        } else {
            // 不修改内容时，不更新修改时间
            $post->timestamps = false;
        }

        if (isset($attributes['isApproved'])) {
            // TODO: 是否有权 审核/放入待审核
            // $this->assertCan($actor, 'rename', $discussion);

            $post->is_approved = $attributes['isApproved'];
        }

        if (isset($attributes['isDeleted'])) {
            // TODO: 是否有权删除
            // $this->assertCan($actor, 'hide', $post);

            if ($attributes['isDeleted']) {
                $post->deleted_user_id = $this->actor->id;
                $post->delete();
            } else {
                $post->deleted_user_id = null;
                $post->restore();
            }
        }

        $this->events->dispatch(
            new Saving($post, $this->actor, $this->data)
        );

        $post->save();

        $this->dispatchEventsFor($post, $this->actor);

        return $post;
    }
}
