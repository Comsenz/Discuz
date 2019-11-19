<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: BatchEditPosts.php xxx 2019-10-31 11:48:00 LiuDongdong $
 */

namespace App\Commands\Post;

use App\Events\Post\Saving;
use App\Models\User;
use App\Repositories\PostRepository;
use Carbon\Carbon;
use Discuz\Foundation\EventsDispatchTrait;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Arr;

class BatchEditPosts
{
    use EventsDispatchTrait;

    /**
     * The ID array of the posts to update.
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
     * The attributes to update on the posts.
     *
     * @var array
     */
    public $data;

    /**
     * @param array $ids
     * @param User $actor
     * @param array $data
     */
    public function __construct(array $ids, User $actor, array $data)
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

        $attributes = Arr::get($this->data, 'attributes', []);
        $result = ['data' => [], 'meta' => []];

        foreach ($this->ids as $id) {
            $post = $posts->query()->whereVisibleTo($this->actor)->find($id);

            if ($post) {
                $post->timestamps = false;
            } else {
                $result['meta'][] = ['id' => $id, 'message' => 'model_not_found'];
                continue;
            }

            if (isset($attributes['isApproved'])) {
                if ($this->actor->can('approve', $post)) {
                    $post->is_approved = $attributes['isApproved'];
                } else {
                    $result['meta'][] = ['id' => $id, 'message' => 'permission_denied'];
                    continue;
                }
            }

            if (isset($attributes['isDeleted'])) {
                if ($this->actor->can('delete', $post)) {
                    if ($attributes['isDeleted']) {
                        $post->deleted_at = Carbon::now();
                        $post->deleted_user_id = $this->actor->id;
                    } else {
                        $post->deleted_at = null;
                        $post->deleted_user_id = null;
                    }
                } else {
                    $result['meta'][] = ['id' => $id, 'message' => 'permission_denied'];
                    continue;
                }
            }

            try {
                $this->events->dispatch(
                    new Saving($post, $this->actor, $this->data)
                );
            } catch (\Exception $e) {
                $result['meta'][] = ['id' => $id, 'message' => $e->getMessage()];
                continue;
            }

            $post->save();

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
