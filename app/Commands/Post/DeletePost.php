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

use App\Events\Post\Deleted;
use App\Events\Post\Deleting;
use App\Models\Post;
use App\Models\User;
use App\Repositories\PostRepository;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Auth\Exception\PermissionDeniedException;
use Discuz\Foundation\EventsDispatchTrait;
use Illuminate\Contracts\Events\Dispatcher;

class DeletePost
{
    use AssertPermissionTrait;
    use EventsDispatchTrait;

    /**
     * The ID of the post to delete.
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
     * 暂未用到，留给插件使用
     *
     * @var array
     */
    public $data;

    /**
     * @param int $postId
     * @param User $actor
     * @param array $data
     */
    public function __construct($postId, User $actor, array $data = [])
    {
        $this->postId = $postId;
        $this->actor = $actor;
        $this->data = $data;
    }

    /**
     * @param Dispatcher $events
     * @param PostRepository $posts
     * @return Post
     * @throws PermissionDeniedException
     */
    public function handle(Dispatcher $events, PostRepository $posts)
    {
        $this->events = $events;

        $post = $posts->findOrFail($this->postId, $this->actor);

        $this->assertCan($this->actor, 'delete', $post);

        $this->events->dispatch(
            new Deleting($post, $this->actor, $this->data)
        );

        $post->raise(new Deleted($post));
        $post->delete();

        $this->dispatchEventsFor($post, $this->actor);

        return $post;
    }
}
