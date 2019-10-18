<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: CreateThread.php xxx 2019-10-11 11:47:00 LiuDongdong $
 */

namespace App\Commands\Thread;

use App\Commands\Post\CreatePost;
use App\Events\Thread\Created;
use App\Events\Thread\Saving;
use App\Models\Thread;
use App\Models\User;
use App\Validators\ThreadValidator;
use Carbon\Carbon;
use Discuz\Censor\Censor;
use Discuz\Foundation\EventsDispatchTrait;
use Exception;
use Illuminate\Contracts\Bus\Dispatcher as BusDispatcher;
use Illuminate\Contracts\Events\Dispatcher as EventDispatcher;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Validator;

class CreateThread
{
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
     * @var Validator
     */
    protected $validator;

    /**
     * CreateThread constructor.
     * @param User $actor
     * @param Collection $data
     * @param $ip
     */
    public function __construct($actor, Collection $data, $ip)
    {
        // TODO: User $actor
        $this->actor = $actor;
        $this->data = $data;
        $this->ip = $ip;
    }

    /**
     * @param EventDispatcher $events
     * @param BusDispatcher $bus
     * @param ThreadValidator $validator
     * @param Thread $thread
     * @param Censor $censor
     * @return Thread
     * @throws ValidationException
     * @throws Exception
     */
    public function handle(EventDispatcher $events, BusDispatcher $bus, ThreadValidator $validator, Thread $thread, Censor $censor)
    {
        $this->events = $events;

        // TODO: 权限验证
        // $this->assertCan($this->actor, 'startDiscussion');

        // 数据验证
        $validator->valid($this->data->all());

        // 敏感词处理
        // $censor->check('');

        // 模型实例
        $thread->user_id = $this->actor->id;
        $thread->created_at = Carbon::now();

        $thread->setRelation('user', $this->actor);

        $thread->raise(new Created($thread));

        $this->events->dispatch(
            new Saving($thread, $this->actor, $this->data->all())
        );

        // 保存模型
        $thread->save();

        try {
            $post = $bus->dispatch(
                new CreatePost($thread->id, $this->actor, $this->data, $this->ip)
            );
        } catch (Exception $e) {
            $thread->delete();

            throw $e;
        }

        $thread->setRawAttributes($post->thread->getAttributes(), true);
        $thread->setLastPost($post);

        $this->dispatchEventsFor($thread, $this->actor);

        $thread->save();

        return $thread;
    }
}
