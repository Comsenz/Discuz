<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Commands\Category;

use App\Events\Category\Deleted;
use App\Events\Category\Deleting;
use App\Models\User;
use App\Repositories\CategoryRepository;
use Discuz\Foundation\EventsDispatchTrait;
use Illuminate\Contracts\Events\Dispatcher;

class BatchDeleteCategories
{
    use EventsDispatchTrait;

    /**
     * The ID array of the categories to delete.
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
     * The attributes of the new categories.
     *
     * @var array
     */
    public $data;

    /**
     * @param array $ids
     * @param User $actor
     * @param array $data
     */
    public function __construct(array $ids, User $actor, array $data = [])
    {
        $this->ids = $ids;
        $this->actor = $actor;
        $this->data = $data;
    }

    /**
     * @param Dispatcher $events
     * @param CategoryRepository $categories
     * @return array
     */
    public function handle(Dispatcher $events, CategoryRepository $categories)
    {
        $this->events = $events;

        $result = ['data' => [], 'meta' => []];

        foreach ($this->ids as $id) {
            $category = $categories->query()->whereVisibleTo($this->actor)->find($id);

            if (! $category) {
                $result['meta'][] = ['id' => $id, 'message' => 'model_not_found'];
                continue;
            }

            if ($this->actor->can('delete', $category)) {
                try {
                    $this->events->dispatch(
                        new Deleting($category, $this->actor, $this->data)
                    );
                } catch (\Exception $e) {
                    $result['meta'][] = ['id' => $id, 'message' => $e->getMessage()];
                    continue;
                }

                $category->raise(new Deleted($category));
                $category->forceDelete();

                $result['data'][] = $category;

                try {
                    $this->dispatchEventsFor($category, $this->actor);
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
