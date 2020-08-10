<?php

/**
 * Copyright (C) 2020 Tencent Cloud.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace App\Commands\Thread;

use App\BlockEditor\BlocksParser;
use App\Censor\Censor;
use App\Commands\Post\CreatePost;
use App\Events\Thread\Created;
use App\Events\Thread\Saving;
use App\Models\Post;
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
     * The current port of the actor.
     *
     * @var int
     */
    public $port;

    /**
     * @param User $actor
     * @param array $data
     * @param string $ip
     * @param string $port
     */
    public function __construct(User $actor, array $data, $ip, $port)
    {
        $this->actor = $actor;
        $this->data = $data;
        $this->ip = $ip;
        $this->port = $port;
    }

    /**
     * @param EventDispatcher $events
     * @param BusDispatcher $bus
     * @param Censor $censor
     * @param Thread $thread
     * @param ThreadValidator $validator
     * @return Thread
     * @throws ValidationException
     * @throws Exception
     */
    public function handle(EventDispatcher $events, BusDispatcher $bus, Censor $censor, Thread $thread, ThreadValidator $validator)
    {
        $this->events = $events;

        //解析内容，检查块各类型权限，检查内容敏感词，检查数据正确性
        $BlocksParser = new BlocksParser(collect(Arr::get($this->data, 'attributes.content')), new Post());
        $blocksTypeList = $BlocksParser->BlocksTypeList();

        $title = $censor->checkText(Arr::get($this->data, 'attributes.title'));
        if ($censor->isMod || $hasVideo = Arr::has($blocksTypeList, 'video')) {
            //存在审核敏感词/存在视频块时，将主题放入待审核
            $thread->is_approved = Thread::UNAPPROVED;
            if ($hasVideo) {
                //设置file_id供事件使用
                $thread->file_ids = $BlocksParser->BlocksValue('video');
            }
        }
        $thread->title = $title;
        $thread->user_id = $this->actor->id;
        $thread->created_at = Carbon::now();

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
                new CreatePost($BlocksParser, $thread->id, $this->actor, $this->data, $this->ip, $this->port)
            );
        } catch (Exception $e) {
            $thread->delete();

            throw $e;
        }

        // 记录触发的审核词
        if ($thread->is_approved === Thread::UNAPPROVED && $censor->wordMod) {
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
