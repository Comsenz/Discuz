<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: BatchCreateCategories.php xxx 2019-11-30 15:22:00 LiuDongdong $
 */

namespace App\Commands\Category;

use App\Events\Category\Saving;
use App\Models\Category;
use App\Models\User;
use App\Validators\CategoryValidator;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Foundation\EventsDispatchTrait;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;

class BatchCreateCategories
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
     * The attributes of the new categories.
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
     * @param CategoryValidator $validator
     * @return array
     */
    public function handle(Dispatcher $events, CategoryValidator $validator)
    {
        $this->events = $events;

        $result = ['data' => [], 'meta' => []];

        if (! $this->actor->can('createCategory')) {
            $result['meta'][] = ['message' => 'permission_denied'];
            return $result;
        }

        foreach ($this->data as $data) {
            $name = Arr::get($data, 'attributes.name');

            $category = Category::build(
                $name,
                Arr::get($data, 'attributes.description'),
                (int) Arr::get($data, 'attributes.sort', 0),
                Arr::get($data, 'attributes.icon', ''),
                $this->ip
            );

            try {
                $this->events->dispatch(
                    new Saving($category, $this->actor, $this->data)
                );
            } catch (\Exception $e) {
                $result['meta'][] = ['name' => $name, 'message' => $e->getMessage()];
                continue;
            }

            try {
                $validator->valid($category->getAttributes());
            } catch (ValidationException $e) {
                $result['meta'][] = ['name' => $name, 'message' => $e->errors()];
                continue;
            }

            $category->save();

            $result['data'][] = $category;

            try {
                $this->dispatchEventsFor($category, $this->actor);
            } catch (\Exception $e) {
                $result['meta'][] = ['name' => $name, 'message' => $e->getMessage()];
                continue;
            }
        }

        return $result;
    }
}
