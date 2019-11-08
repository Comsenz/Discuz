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
use App\Models\User;
use App\Repositories\ThreadRepository;
use App\Validators\PostValidator;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Auth\Exception\PermissionDeniedException;
use Discuz\Foundation\EventsDispatchTrait;
use Illuminate\Contracts\Events\Dispatcher as EventDispatcher;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;

class CreatePost
{
    use AssertPermissionTrait;
    use EventsDispatchTrait;

    /**
     * The id of the thread.
     *
     * @var int
     */
    public $threadId;

    /**
     * The id of the post waiting to be replied.
     *
     * @var int
     */
    public $replyId;

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
     * @param array $data
     * @param null $ip
     */
    public function __construct($threadId, User $actor, array $data, $ip = null)
    {
        $this->threadId = $threadId;
        $this->replyId = Arr::get($data, 'attributes.replyId', null);
        $this->actor = $actor;
        $this->data = $data;
        $this->ip = $ip;
    }

    /**
     * @param EventDispatcher $events
     * @param ThreadRepository $threads
     * @param PostValidator $validator
     * @return Post
     * @throws PermissionDeniedException
     * @throws ValidationException
     */
    public function handle(EventDispatcher $events, ThreadRepository $threads, PostValidator $validator)
    {
        $this->events = $events;

        $thread = $threads->findOrFail($this->threadId);

        $isFirst = empty($thread->last_posted_user_id);

        if (!$isFirst) {
            // 非首帖，检查是否有权回复
            $this->assertCan($this->actor, 'reply', $thread);

            // 回复另一条回复时，检查是否在同一主题下的
            if (!empty($this->replyId)) {
                if (Post::where([['id', $this->replyId], ['thread_id', $thread->id]])->doesntExist()) {
                    throw (new ModelNotFoundException);
                }
            }
        }

        $post = Post::reply(
            $thread->id,
            Arr::get($this->data, 'attributes.content'),
            $this->actor->id,
            $this->ip,
            $this->replyId,
            $isFirst
        );

        $this->events->dispatch(
            new Saving($post, $this->actor, $this->data)
        );

        $validator->valid($post->getAttributes());

        $post->save();

        // TODO: 通知相关用户，在给定的整个持续时间内，每位用户只能收到一个通知
        // $this->notifications->onePerUser(function () use ($post, $actor) {
            $this->dispatchEventsFor($post, $this->actor);
        // });

        return $post;
    }
}
