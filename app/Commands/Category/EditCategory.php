<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 */

namespace App\Commands\Category;

use App\Events\Category\Saving;
use App\Models\User;
use App\Repositories\CategoryRepository;
use App\Validators\CategoryValidator;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Auth\Exception\PermissionDeniedException;
use Discuz\Foundation\EventsDispatchTrait;
use Illuminate\Contracts\Events\Dispatcher as EventDispatcher;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;

class EditCategory
{
    use AssertPermissionTrait;
    use EventsDispatchTrait;

    /**
     * The ID of the category to edit.
     *
     * @var int
     */
    public $categoryId;

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
     * @param $categoryId
     * @param User $actor
     * @param array $data
     * @param string $ip
     */
    public function __construct($categoryId, User $actor, array $data, string $ip)
    {
        $this->categoryId = $categoryId;
        $this->actor = $actor;
        $this->data = $data;
        $this->ip = $ip;
    }

    /**
     * @param EventDispatcher $events
     * @param CategoryRepository $categories
     * @param CategoryValidator $validator
     * @return mixed
     * @throws PermissionDeniedException
     * @throws ValidationException
     */
    public function handle(EventDispatcher $events, CategoryRepository $categories, CategoryValidator $validator)
    {
        $this->events = $events;

        $category = $categories->findOrFail($this->categoryId, $this->actor);

        $this->assertCan($this->actor, 'edit', $category);

        $attributes = Arr::get($this->data, 'attributes', []);

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

        $this->events->dispatch(
            new Saving($category, $this->actor, $this->data)
        );

        $validator->valid($category->getDirty());

        $category->save();

        $this->dispatchEventsFor($category, $this->actor);

        return $category;
    }
}
