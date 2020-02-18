<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Commands\Thread;

use App\Censor\Censor;
use App\Commands\Post\CreatePost;
use App\Events\Thread\Created;
use App\Events\Thread\Saving;
use App\Models\PostMod;
use App\Models\Thread;
use App\Models\User;
use App\Validators\ThreadValidator;
use Carbon\Carbon;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Auth\Exception\PermissionDeniedException;
use Discuz\Foundation\EventsDispatchTrait;
use Exception;
use Illuminate\Contracts\Bus\Dispatcher as BusDispatcher;
use Illuminate\Contracts\Events\Dispatcher as EventDispatcher;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;

class CreateThread
{
    use AssertPermissionTrait;
    use EventsDispatchTrait;

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
     * CreateThread constructor.
     * @param User $actor
     * @param array $data
     * @param $ip
     */
    public function __construct(User $actor, array $data, $ip)
    {
        $this->actor = $actor;
        $this->data = $data;
        $this->ip = $ip;
    }

    /**
     * @param EventDispatcher $events
     * @param BusDispatcher $bus
     * @param Censor $censor
     * @param Thread $thread
     * @param ThreadValidator $validator
     * @return Thread
     * @throws ValidationException
     * @throws PermissionDeniedException
     * @throws Exception
     */
    public function handle(EventDispatcher $events, BusDispatcher $bus, Censor $censor, Thread $thread, ThreadValidator $validator)
    {
        $this->events = $events;

        $this->assertCan($this->actor, 'createThread');

        // 敏感词校验
        $content = $censor->checkText(Arr::get($this->data, 'attributes.content'));
        Arr::set($this->data, 'attributes.content', $content);

        // 存在审核敏感词时，将主题放入待审核
        if ($censor->isMod) {
            $thread->is_approved = 0;
        }

        $thread->user_id = $this->actor->id;
        $thread->created_at = Carbon::now();
        $thread->is_long_article = (bool) Arr::get($this->data, 'attributes.is_long_article');

        // 发布长文时记录标题及价格
        if ($thread->is_long_article) {
            $thread->title = Arr::get($this->data, 'attributes.title');
            $thread->price = (float) Arr::get($this->data, 'attributes.price', 0);
        }

        $thread->setRelation('user', $this->actor);

        $thread->raise(new Created($thread));

        $this->events->dispatch(
            new Saving($thread, $this->actor, $this->data)
        );

        $validator->valid($thread->getAttributes());

        $thread->save();

        try {
            $post = $bus->dispatch(
                new CreatePost($thread->id, $this->actor, $this->data, $this->ip)
            );
        } catch (Exception $e) {
            $thread->delete();

            throw $e;
        }

        // 记录触发的审核词
        if ($thread->is_approved == 0 && $censor->wordMod) {
            $stopWords = new PostMod;
            $stopWords->stop_word = implode(',', $censor->wordMod);

            $post->stopWords()->save($stopWords);
        }

        $thread->setRawAttributes($post->thread->getAttributes(), true);
        $thread->setLastPost($post);

        $this->dispatchEventsFor($thread, $this->actor);

        $thread->save();

        return $thread;
    }
}
