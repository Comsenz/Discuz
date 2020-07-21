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
use App\Models\User;
use App\Repositories\PostRepository;
use Discuz\Foundation\EventsDispatchTrait;
use Illuminate\Contracts\Events\Dispatcher;

class BatchDeletePosts
{
    use EventsDispatchTrait;

    /**
     * The ID array of the threads to delete.
     *
     * @var array
     */
    public $ids;

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
     * @param $ids
     * @param User $actor
     * @param array $data
     */
    public function __construct($ids, User $actor, array $data = [])
    {
        $this->ids = $ids;
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

        foreach ($this->ids as $id) {
            $post = $posts->query()->whereVisibleTo($this->actor)->find($id);

            if (! $post) {
                $result['meta'][] = ['id' => $id, 'message' => 'model_not_found'];
                continue;
            }

            if ($this->actor->can('delete', $post)) {
                try {
                    $this->events->dispatch(
                        new Deleting($post, $this->actor, $this->data)
                    );
                } catch (\Exception $e) {
                    $result['meta'][] = ['id' => $id, 'message' => $e->getMessage()];
                    continue;
                }

                $post->raise(new Deleted($post));
                $post->delete();

                $result['data'][] = $post;

                try {
                    $this->dispatchEventsFor($post, $this->actor);
                } catch (\Exception $e) {
                    $result['meta'][] = ['id' => $id, 'message' => $e->getMessage()];
                    continue;
                }
            } else {
                $result['meta'][] = ['id' => $id, 'message' => 'permission_denied'];
                continue;
            }
        }

        return $result;
    }
}
