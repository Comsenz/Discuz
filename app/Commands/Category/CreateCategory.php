<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 */

namespace App\Commands\Category;

use App\Models\Category;
use App\Models\User;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Auth\Exception\NotAuthenticatedException;
use Discuz\Auth\Exception\PermissionDeniedException;
use App\Events\Category\Saving;
use Discuz\Foundation\EventsDispatchTrait;
use Illuminate\Contracts\Events\Dispatcher as EventDispatcher;
use Illuminate\Support\Arr;

class CreateCategory
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
     * The attributes of the new category.
     *
     * @var array
     */
    public $data;

    /**
     * The IP address of the actor..
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
     * @param EventDispatcher $events
     * @return Category
     * @throws NotAuthenticatedException
     * @throws PermissionDeniedException
     */
    public function handle(EventDispatcher $events)
    {
        $this->events = $events;

        $this->assertRegistered($this->actor);
        $this->assertCan($this->actor, 'createCategory');

        $attributes = Arr::get($this->data, 'attributes');

        $category = Category::build(
            $attributes['name'],
            $attributes['description'],
            $attributes['sort'],
            $attributes['icon'],
            $this->ip
        );

        $this->events->dispatch(
            new Saving($category, $this->actor, $this->data)
        );

        $category->save();

        $this->dispatchEventsFor($category);

        return $category;
    }
}
