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
     * @throws PermissionDeniedException
     * @throws ValidationException
     */
    public function handle(EventDispatcher $events, BusDispatcher $bus, Censor $censor, Thread $thread, ThreadValidator $validator)
    {
        $this->events = $events;

        // Check Permissions
        $this->assertCan($this->actor, 'createThread');
        $thread->type = (int) Arr::get($this->data, 'attributes.type', 0);
        if ($thread->type == 1) {
            $this->assertCan($this->actor, 'createThreadLong');

            // 是否有权发布音频
            if (Arr::get($this->data, 'attributes.file_id', '')) {
                $this->assertCan($this->actor, 'createAudio');
            }
        } elseif ($thread->type == 2) {
            $this->assertCan($this->actor, 'createThreadVideo');
        } elseif ($thread->type == 3) {
            $this->assertCan($this->actor, 'createThreadImage');
        }

        // 敏感词校验
        $title = $censor->checkText(Arr::get($this->data, 'attributes.title'));
        $content = $censor->checkText(Arr::get($this->data, 'attributes.content'));
        Arr::set($this->data, 'attributes.content', $content);

        // 存在审核敏感词/发布视频主题时，将主题放入待审核
        if ($censor->isMod || $thread->type == 2) {
            $thread->is_approved = 0;
        }

        $thread->user_id = $this->actor->id;
        $thread->created_at = Carbon::now();

        // 长文帖需要设置标题
        if ($thread->type == 1) {
            $thread->title = $title;
        }

        // 非文字贴可设置价格
        if ($thread->type != 0) {
            $thread->price = (float) Arr::get($this->data, 'attributes.price', 0);

            // 付费长文帖可设置免费阅读字数
            if ($thread->type == 1 && $thread->price) {
                $thread->free_words = (int) Arr::get($this->data, 'attributes.free_words', 0);
            }
        }

        $thread->setRelation('user', $this->actor);

        $thread->raise(new Created($thread));

        $this->events->dispatch(
            new Saving($thread, $this->actor, $this->data)
        );

        // 发帖验证码
        $captcha = '';  // 默认为空将不走验证
        if (!$this->actor->isAdmin() && $this->actor->can('createThreadWithCaptcha')) {
            $captcha = [
                Arr::get($this->data, 'attributes.captcha_ticket', ''),
                Arr::get($this->data, 'attributes.captcha_rand_str', ''),
                $this->ip,
            ];
        }

        $validator->valid($thread->getAttributes() + compact('captcha'));

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
            $stopWords->stop_word = implode(',', array_unique($censor->wordMod));

            $post->stopWords()->save($stopWords);
        }

        $thread->setRawAttributes($post->thread->getAttributes(), true);
        $thread->setLastPost($post);

        $this->dispatchEventsFor($thread, $this->actor);

        $thread->save();

        return $thread;
    }
}
