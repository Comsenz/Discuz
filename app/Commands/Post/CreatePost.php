<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: CreateThread.php xxx 2019-10-11 11:47:00 LiuDongdong $
 */

namespace App\Commands\Post;

use App\Events\Post\Saving;
use App\Models\Post;
use App\Models\Thread;
use App\Models\User;
use Discuz\Foundation\EventsDispatchTrait;
use Exception;
use Illuminate\Contracts\Events\Dispatcher as EventDispatcher;
use Illuminate\Support\Collection;

class CreatePost
{
    use EventsDispatchTrait;
    // use AssertPermissionTrait;

    /**
     * The id of the new thread.
     *
     * @var int
     */
    public $threadId;

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
     * @param $threadId
     * @param User $actor
     * @param Collection $data
     * @param null $ip
     */
    public function __construct($threadId, $actor, Collection $data, $ip = null)
    {
        // TODO: User $actor
        $this->threadId = $threadId;
        $this->actor = $actor;
        $this->data = $data;
        $this->ip = $ip;
    }

    /**
     * @param EventDispatcher $events
     * @return Post
     * @throws Exception
     */
    public function handle(EventDispatcher $events)
    {
        $this->events = $events;

        // TODO: 权限验证（是否有权查看，是否有权回复）
        // $this->assertCan($this->actor, 'startDiscussion');

        $thread = Thread::findOrFail($this->threadId);

        // 是否首帖
        // if ($discussion->post_number_index > 0) {
        //     $this->assertCan($actor, 'reply', $discussion);
        // }
        $isFirst = empty($thread->last_posted_user_id);

        // 一个 post 实例，在入库前前确保插件可以使用它
        $post = Post::reply(
            $thread->id,
            $this->data->get('content'),
            $this->actor->id,
            $this->ip,
            $isFirst
        );

        // 管理员
        // if ($this->actor->isAdmin() && ($time = Arr::get($command->data, 'attributes.createdAt'))) {
        //     $post->created_at = new Carbon($time);
        // }

        $this->events->dispatch(
            new Saving($post, $this->actor, $this->data->all())
        );

        // $this->validator->assertValid($post->getAttributes());

        $post->save();

        // 在给定的整个持续时间内，每位用户只能收到一个通知
        // $this->notifications->onePerUser(function () use ($post, $actor) {
            $this->dispatchEventsFor($post, $this->actor);
        // });

        return $post;
    }
}
