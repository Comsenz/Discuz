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

use App\Events\Post\PostWasApproved;
use App\Events\Post\Saved;
use App\Events\Post\Saving;
use App\Models\Post;
use App\Models\User;
use App\Repositories\PostRepository;
use Discuz\Foundation\EventsDispatchTrait;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Arr;

class BatchEditPosts
{
    use EventsDispatchTrait;

    /**
     * The user performing the action.
     *
     * @var User
     */
    public $actor;

    /**
     * The attributes to update on the posts.
     *
     * @var array
     */
    public $data;

    /**
     * @param User $actor
     * @param array $data
     */
    public function __construct(User $actor, array $data)
    {
        $this->actor = $actor;
        $this->data = $data;
    }

    /**
     * @param Dispatcher $events
     * @param PostRepository $posts
     * @return array
     */
    public function handle(Dispatcher $events, PostRepository $posts)
    {
        $this->events = $events;

        $result = ['data' => [], 'meta' => []];

        foreach ($this->data as $data) {
            if (isset($data['id'])) {
                $id = $data['id'];
            } else {
                continue;
            }

            /** @var Post $post */
            $post = $posts->query()->whereVisibleTo($this->actor)->find($id);

            if ($post) {
                $post->timestamps = false;
            } else {
                $result['meta'][] = ['id' => $id, 'message' => 'model_not_found'];
                continue;
            }

            $attributes = Arr::get($data, 'attributes', []);

            if (isset($attributes['isApproved']) && $attributes['isApproved'] < 3) {
                if ($this->actor->can('approve', $post)) {
                    if ($post->is_approved != $attributes['isApproved']) {
                        $post->is_approved = $attributes['isApproved'];

                        $post->raise(
                            new PostWasApproved($post, $this->actor, ['message' => $attributes['message'] ?? ''])
                        );
                    }
                } else {
                    $result['meta'][] = ['id' => $id, 'message' => 'permission_denied'];
                    continue;
                }
            }

            if (isset($attributes['isDeleted'])) {
                if ($this->actor->can('hide', $post)) {
                    $message = $attributes['message'] ?? '';

                    if ($attributes['isDeleted']) {
                        $post->hide($this->actor, ['message' => $message]);
                    } else {
                        $post->restore($this->actor, ['message' => $message]);
                    }
                } else {
                    $result['meta'][] = ['id' => $id, 'message' => 'permission_denied'];
                    continue;
                }
            }

            try {
                $this->events->dispatch(
                    new Saving($post, $this->actor, $data)
                );
            } catch (\Exception $e) {
                $result['meta'][] = ['id' => $id, 'message' => $e->getMessage()];
                continue;
            }

            $post->save();

            $post->raise(new Saved($post, $this->actor, $data));

            $result['data'][] = $post;

            try {
                $this->dispatchEventsFor($post, $this->actor);
            } catch (\Exception $e) {
                $result['meta'][] = ['id' => $id, 'message' => $e->getMessage()];
                continue;
            }
        }

        return $result;
    }
}
