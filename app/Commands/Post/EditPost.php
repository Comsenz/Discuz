<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: EditPost.php xxx 2019-10-24 15:10:00 LiuDongdong $
 */

namespace App\Commands\Post;

use App\Models\Post;
use App\Models\User;
use App\Validators\PostValidator;
use Discuz\Foundation\EventsDispatchTrait;
use Illuminate\Contracts\Events\Dispatcher as EventDispatcher;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

class EditPost
{
    use EventsDispatchTrait;
    // use AssertPermissionTrait;

    /**
     * The id of the post.
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
     * The attributes of the new thread.
     *
     * @var array
     */
    public $data;

    /**
     * The current ip address of the actor.
     *
     * @var array
     */
    public $ip;

    /**
     * @param $postId
     * @param User $actor
     * @param Collection $data
     * @param null $ip
     */
    public function __construct($postId, $actor, Collection $data, $ip = null)
    {
        // TODO: User $actor
        $this->postId = $postId;
        $this->actor = $actor;
        $this->data = $data;
        $this->ip = $ip;
    }

    /**
     * @param EventDispatcher $events
     * @param PostValidator $validator
     * @return Post
     * @throws ValidationException
     */
    public function handle(EventDispatcher $events, PostValidator $validator)
    {
        $this->events = $events;

        // TODO: 权限验证（是否有权查看）
        // $this->assertCan($this->actor, 'startDiscussion');

        // 数据验证
        $validator->valid($this->data->all());

        $post = Post::findOrFail($this->postId);

        if ($this->data->has('content')) {
            // TODO: 是否有权修改内容
            // $this->assertCan($actor, 'rename', $discussion);

            $post->content = $this->data->get('content');
        }

        if ($this->data->has('isApproved')) {
            // TODO: 是否有权 审核/放入待审核
            // $this->assertCan($actor, 'rename', $discussion);

            $post->is_approved = $this->data->get('isApproved');
        }

        if ($this->data->has('isDelete')) {
            // TODO: 是否有权删除
            // $this->assertCan($actor, 'hide', $discussion);

            if ($this->data->get('isDelete')) {
                $post->deleted_user_id = $this->actor->id;
                $post->delete();
            } else {
                $post->deleted_user_id = null;
                $post->restore();
            }
        }

        $post->save();

        $this->dispatchEventsFor($post, $this->actor);

        return $post;
    }
}
