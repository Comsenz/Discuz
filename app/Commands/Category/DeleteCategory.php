<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Commands\Category;

use App\Events\Category\Deleting;
use App\Models\Category;
use App\Models\User;
use App\Repositories\CategoryRepository;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Auth\Exception\PermissionDeniedException;
use Discuz\Foundation\EventsDispatchTrait;
use Exception;
use Illuminate\Contracts\Events\Dispatcher;

class DeleteCategory
{
    use AssertPermissionTrait;
    use EventsDispatchTrait;

    /**
     * The ID of the category to delete.
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
     * 暂未用到，留给插件使用
     *
     * @var array
     */
    public $data;

    /**
     * @param $categoryId
     * @param User $actor
     * @param array $data
     */
    public function __construct($categoryId, User $actor, array $data = [])
    {
        $this->categoryId = $categoryId;
        $this->actor = $actor;
        $this->data = $data;
    }

    /**
     * @param Dispatcher $events
     * @param CategoryRepository $categories
     * @return Category
     * @throws PermissionDeniedException
     * @throws Exception
     */
    public function handle(Dispatcher $events, CategoryRepository $categories)
    {
        $this->events = $events;

        $category = $categories->findOrFail($this->categoryId, $this->actor);

        $this->assertCan($this->actor, 'delete', $category);

        // 分类下有主题时不能删除
        if ($category->threads()->first('id')) {
            throw new Exception('cannot_delete_category_with_threads');
        }

        $this->events->dispatch(
            new Deleting($category, $this->actor, $this->data)
        );

        $category->delete();

        $this->dispatchEventsFor($category, $this->actor);

        return $category;
    }
}
