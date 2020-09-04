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

namespace App\Commands\Category;

use App\Events\Category\Saving;
use App\Models\User;
use App\Repositories\CategoryRepository;
use App\Validators\CategoryValidator;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Foundation\EventsDispatchTrait;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;

class BatchEditCategories
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
     * The attributes to update on the category.
     *
     * @var array
     */
    public $data;

    /**
     * The IP address of the actor.
     *
     * @var string
     */
    public $ip;

    /**
     * @param User $actor
     * @param array $data
     * @param string $ip
     */
    public function __construct(User $actor, array $data, string $ip)
    {
        $this->actor = $actor;
        $this->data = $data;
        $this->ip = $ip;
    }

    /**
     * @param Dispatcher $events
     * @param CategoryRepository $categories
     * @param CategoryValidator $validator
     * @return array
     */
    public function handle(Dispatcher $events, CategoryRepository $categories, CategoryValidator $validator)
    {
        $this->events = $events;

        $result = ['data' => [], 'meta' => []];

        foreach ($this->data as $data) {
            if (isset($data['id'])) {
                $id = $data['id'];
            } else {
                continue;
            }

            $category = $categories->query()->whereVisibleTo($this->actor)->find($id);

            if (! $category) {
                $result['meta'][] = ['id' => $id, 'message' => 'model_not_found'];
                continue;
            }

            if ($this->actor->can('edit', $category)) {
                $attributes = Arr::get($data, 'attributes', []);

                if (isset($attributes['name'])) {
                    $category->name = $attributes['name'];
                }

                if (isset($attributes['description'])) {
                    $category->description = $attributes['description'];
                }

                if (isset($attributes['sort'])) {
                    $category->sort = $attributes['sort'];
                }

                if (isset($attributes['icon'])) {
                    $category->icon = $attributes['icon'];
                }

                if (isset($attributes['property'])) {
                    $category->property = $attributes['property'];
                }

                if (isset($attributes['moderators'])) {
                    $category->moderators = User::query()
                        ->where('status', 0)
                        ->whereIn('moderators', (array) $attributes['moderators'])
                        ->pluck('id')
                        ->join(',');
                }
            } else {
                $result['meta'][] = ['id' => $id, 'message' => 'permission_denied'];
                continue;
            }

            try {
                $this->events->dispatch(
                    new Saving($category, $this->actor, $data)
                );
            } catch (\Exception $e) {
                $result['meta'][] = ['id' => $id, 'message' => $e->getMessage()];
                continue;
            }

            try {
                $validator->valid($category->getDirty());
            } catch (ValidationException $e) {
                $result['meta'][] = ['id' => $id, 'message' => $e->errors()];
                continue;
            }

            $category->save();

            $result['data'][] = $category;

            try {
                $this->dispatchEventsFor($category, $this->actor);
            } catch (\Exception $e) {
                $result['meta'][] = ['id' => $id, 'message' => $e->getMessage()];
                continue;
            }
        }

        return $result;
    }
}
