<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: CreateThread.php xxx 2019-10-11 11:47:00 LiuDongdong $
 */

namespace App\Commands\Post;

use App\Censor\Censor;
use App\Events\Post\Created;
use App\Events\Post\Saved;
use App\Events\Post\Saving;
use App\Models\Post;
use App\Models\User;
use App\Repositories\ThreadRepository;
use App\Validators\PostValidator;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Auth\Exception\PermissionDeniedException;
use Discuz\Foundation\EventsDispatchTrait;
use Illuminate\Contracts\Events\Dispatcher;
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
    public $replyPostId;

    /**
     * The id of the post waiting to be replied.
     *
     * @var int
     */
    public $replyUserId;

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
     * @var string
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
        $this->replyPostId = Arr::get($data, 'attributes.replyId', null);
        $this->actor = $actor;
        $this->data = $data;
        $this->ip = $ip;
    }

    /**
     * @param Dispatcher $events
     * @param ThreadRepository $threads
     * @param PostValidator $validator
     * @param Censor $censor
     * @param Post $post
     * @return Post
     * @throws PermissionDeniedException
     * @throws ValidationException
     */
    public function handle(Dispatcher $events, ThreadRepository $threads, PostValidator $validator, Censor $censor, Post $post)
    {
        $this->events = $events;

        $thread = $threads->findOrFail($this->threadId);

        $isFirst = empty($thread->last_posted_user_id);

        if (! $isFirst) {
            // 非首帖，检查是否有权回复
            $this->assertCan($this->actor, 'reply', $thread);

            // 敏感词校验
            $content = $censor->check(Arr::get($this->data, 'attributes.content'));
            Arr::set($this->data, 'attributes.content', $content);

            // 存在审核敏感词时，将回复内容放入待审核
            if ($censor->isMod) {
                $post->is_approved = 0;
            }

            // 回复另一条回复时，检查是否在同一主题下的
            if (! empty($this->replyPostId)) {
                $this->replyUserId = $post->where('id', $this->replyPostId)
                    ->where('thread_id', $thread->id)
                    ->value('user_id');

                if (! $this->replyUserId) {
                    throw (new ModelNotFoundException);
                }
            }
        }

        $post = $post->reply(
            $thread->id,
            Arr::get($this->data, 'attributes.content'),
            $this->actor->id,
            $this->ip,
            $this->replyPostId,
            $this->replyUserId,
            $isFirst
        );

        $post->raise(new Created($post, $this->actor, $this->data));

        $this->events->dispatch(
            new Saving($post, $this->actor, $this->data)
        );

        $validator->valid($post->getAttributes());

        $post->save();

        $post->raise(new Saved($post, $this->actor, $this->data));

        $post->raise(new Created($post, $this->actor, $this->data));

        // TODO: 通知相关用户，在给定的整个持续时间内，每位用户只能收到一个通知
        // $this->notifications->onePerUser(function () use ($post, $actor) {
            $this->dispatchEventsFor($post, $this->actor);
        // });

        return $post;
    }
}
