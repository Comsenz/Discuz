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

namespace App\Commands\Post;

use App\BlockEditor\BlocksParser;
use App\Censor\Censor;
use App\Events\Post\Created;
use App\Events\Post\Saved;
use App\Events\Post\Saving;
use App\Models\Post;
use App\Models\Thread;
use App\Models\User;
use App\Repositories\ThreadRepository;
use App\Validators\PostValidator;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Auth\Exception\PermissionDeniedException;
use Discuz\Foundation\EventsDispatchTrait;
use Exception;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;

class CreatePost
{
    use AssertPermissionTrait;
    use EventsDispatchTrait;

    public $BlocksParser;

    public $content;

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
     * The current port of the actor.
     *
     * @var int
     */
    public $port;

    /**
     * @param BlocksParser $BlocksParser
     * @param $content
     * @param int $threadId
     * @param User $actor
     * @param array $data
     * @param string $ip
     * @param int $port
     */
    public function __construct($BlocksParser, $content, $threadId, User $actor, array $data, $ip, $port)
    {
        $this->BlocksParser = $BlocksParser;
        $this->content = $content;
        $this->threadId = $threadId;
        $this->replyPostId = Arr::get($data, 'attributes.replyId', null);
        $this->actor = $actor;
        $this->data = $data;
        $this->ip = $ip;
        $this->port = $port;
    }

    /**
     * @param Dispatcher $events
     * @param ThreadRepository $threads
     * @param PostValidator $validator
     * @param Censor $censor
     * @param Post $post
     * @return Post
     * @throws PermissionDeniedException
     * @throws Exception
     * @throws ValidationException
     */
    public function handle(Dispatcher $events, ThreadRepository $threads, PostValidator $validator, Censor $censor, Post $post)
    {
        $this->events = $events;

        $thread = $threads->findOrFail($this->threadId);

        $isFirst = empty($thread->post_count);

        $isComment = (bool) Arr::get($this->data, 'attributes.isComment');
        $audioList = $this->BlocksParser->BlocksValue('audio');

        $isMod = false;
        foreach ($this->content->get('blocks') as $block) {
            if ($block['type'] == 'text' && isset($block['data']['isMod']) && $block['data']['isMod']) {
                $isMod = $block['data']['isMod'];
                break;
            }
        }

        if (! $isFirst) {
            // 非首帖，检查是否有权回复
            $this->assertCan($this->actor, 'reply', $thread);

            // 引用回复
            if (! empty($this->replyPostId)) {
                // 不能只回复引用部分
                $pattern = '/<blockquote class="quoteCon">.*<\/blockquote>/';
                $replyContent = '';
                foreach ($this->content->get('blocks') as $block) {
                    if ($block['type'] == 'text' && $replyContent = preg_replace($pattern, '', $block['data']['value'])) {
                        break;
                    }
                }

                if (! $replyContent) {
                    throw new Exception('reply_content_cannot_null');
                }

                // 检查是否在同一主题下的
                $this->replyUserId = $post->newQuery()
                    ->where('id', $this->replyPostId)
                    ->where('thread_id', $thread->id)
                    ->value('user_id');

                if (! $this->replyUserId) {
                    throw new ModelNotFoundException;
                }
            }

        } else {
            //发布的是首帖时修改主题审核属性
            if ($isMod) {
                $thread->is_approved = Thread::UNAPPROVED;
                $thread->save();
            }
        }

        $post = $post->reply(
            $thread->id,
            $this->content,
            $this->actor->id,
            $this->ip,
            $this->port,
            $this->replyPostId,
            $this->replyUserId,
            $isFirst,
            $isComment,
            Arr::get($this->data, 'attributes.latitude', 0),
            Arr::get($this->data, 'attributes.longitude', 0)
        );

        // 存在审核敏感词时，将回复内容放入待审核
        if ($isMod) {
            $post->is_approved = Post::UNAPPROVED;
        } else {
            $post->is_approved = Post::APPROVED;
        }

        //音频关系解析
        if (count($audioList) > 0) {
            $post->file_ids = $audioList;
        }

        $post->raise(new Created($post, $this->actor, $this->data));

        $this->events->dispatch(
            new Saving($post, $this->actor, $this->data)
        );

        $validator->valid($post->getAttributes());

        $post->save();

        $post->raise(new Saved($post, $this->actor, $this->data));

        // TODO: 通知相关用户，在给定的整个持续时间内，每位用户只能收到一个通知
        // $this->notifications->onePerUser(function () use ($post, $actor) {
        $this->dispatchEventsFor($post, $this->actor);
        // });

        return $post;
    }
}
