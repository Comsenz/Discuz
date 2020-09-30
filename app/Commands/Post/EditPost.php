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

use App\Censor\Censor;
use App\Events\Post\PostWasApproved;
use App\Events\Post\Revising;
use App\Events\Post\Saved;
use App\Events\Post\Saving;
use App\Models\Post;
use App\Models\PostMod;
use App\Models\Thread;
use App\Models\User;
use App\Repositories\PostRepository;
use App\Validators\PostValidator;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Auth\Exception\PermissionDeniedException;
use Discuz\Foundation\EventsDispatchTrait;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;

class EditPost
{
    use AssertPermissionTrait;
    use EventsDispatchTrait;

    /**
     * The ID of the post to edit.
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
     * The attributes to update on the post.
     *
     * @var array
     */
    public $data;

    /**
     * @param int $postId The ID of the post to edit.
     * @param User $actor The user performing the action.
     * @param array $data The attributes to update on the post.
     */
    public function __construct($postId, User $actor, array $data)
    {
        $this->postId = $postId;
        $this->actor = $actor;
        $this->data = $data;
    }

    /**
     * @param Dispatcher $events
     * @param PostRepository $posts
     * @param Censor $censor
     * @param PostValidator $validator
     * @return Post
     * @throws PermissionDeniedException
     * @throws ValidationException
     */
    public function handle(Dispatcher $events, PostRepository $posts, Censor $censor, PostValidator $validator)
    {
        $this->events = $events;

        $post = $posts->findOrFail($this->postId, $this->actor);

        $attributes = Arr::get($this->data, 'attributes', []);

        if (isset($attributes['content'])) {
            $this->assertCan($this->actor, 'edit', $post);

            $post->raise(new Revising($post, $this->actor, $this->data));

            // 敏感词校验
            $content = $censor->checkText($attributes['content']);

            // 视频帖、图片帖不传内容时设置默认内容
            if ($post->is_first && empty(trim($content))) {
                switch ($post->thread->type) {
                    case Thread::TYPE_OF_VIDEO:
                        $content = '分享视频';
                        break;
                    case Thread::TYPE_OF_IMAGE:
                        $content = '分享图片';
                        break;
                    default:
                        $content = '分享';
                }
            }

            // 存在审核敏感词时，将主题放入待审核
            if ($censor->isMod) {
                $post->is_approved = Post::UNAPPROVED;
            }

            $post->revise($content, $this->actor);
        } else {
            // 不修改内容时，不更新修改时间
            $post->timestamps = false;
        }

        if (isset($attributes['isApproved']) && $attributes['isApproved'] < 3) {
            $this->assertCan($this->actor, 'approve', $post);

            if ($post->is_approved != $attributes['isApproved']) {
                $post->is_approved = $attributes['isApproved'];

                $post->raise(
                    new PostWasApproved($post, $this->actor, ['message' => $attributes['message'] ?? ''])
                );
            }
        }

        if (isset($attributes['isDeleted'])) {
            $this->assertCan($this->actor, 'hide', $post);

            if ($attributes['isDeleted']) {
                $post->hide($this->actor, ['message' => $attributes['message'] ?? '']);
            } else {
                $post->restore($this->actor, ['message' => $attributes['message'] ?? '']);
            }
        }

        $this->events->dispatch(
            new Saving($post, $this->actor, $this->data)
        );

        $validator->valid($post->getDirty());

        // 记录触发的审核词
        if ($post->is_approved === Post::UNAPPROVED && $censor->wordMod) {
            /** @var PostMod $stopWords */
            $stopWords = PostMod::query()->firstOrNew(['post_id' => $post->id]);

            $stopWords->stop_word = implode(',', array_unique($censor->wordMod));

            $post->stopWords()->save($stopWords);
        }

        $post->save();

        $post->raise(new Saved($post, $this->actor, $this->data));

        $this->dispatchEventsFor($post, $this->actor);

        return $post;
    }
}
