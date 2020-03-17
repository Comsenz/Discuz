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
use App\Settings\SettingsRepository;
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
     * @param SettingsRepository $settings
     * @return Thread
     * @throws PermissionDeniedException
     * @throws ValidationException
     */
    public function handle(EventDispatcher $events, BusDispatcher $bus, Censor $censor, Thread $thread, ThreadValidator $validator, SettingsRepository $settings)
    {
        $this->events = $events;

        // Check Permissions
        $this->assertCan($this->actor, 'createThread');
        $thread->type = (int) Arr::get($this->data, 'attributes.type', 0);
        if ($thread->type == 1) {
            $this->assertCan($this->actor, 'createThreadLong');
        } elseif ($thread->type == 2) {
            $this->assertCan($this->actor, 'createThreadVideo');
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

        // 发布长文时记录标题及价格，发布视频时记录价格
        if ($thread->type == 1) {
            $thread->title = $title;
        }
        if ($thread->type != 0) {
            $thread->price = (float) Arr::get($this->data, 'attributes.price', 0);
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
        //视频贴验证是否上传视频
        $file_id = '';
        $file_name = '';
        if ($thread->type == 2) {
            $file_id = Arr::get($this->data, 'attributes.file_id', '');
            $file_name = Arr::get($this->data, 'attributes.file_name', '');
        }

        $validator->valid($thread->getAttributes() + compact('captcha', 'file_id', 'file_name'));

        $thread->save();

        try {
            $post = $bus->dispatch(
                new CreatePost($thread->id, $this->actor, $this->data, $this->ip)
            );
        } catch (Exception $e) {
            $thread->delete();

            throw $e;
        }

        // 视频主题存储相关数据
        if ($thread->type == 2) {
            $threadVideo = $bus->dispatch(
                new CreateThreadVideo($this->actor, $thread->id, $this->data)
            );
            $thread->setRelation('threadVideo', $threadVideo);
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
