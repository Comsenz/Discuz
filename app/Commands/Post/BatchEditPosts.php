<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: BatchEditPosts.php xxx 2019-10-31 11:48:00 LiuDongdong $
 */

namespace App\Commands\Post;

use App\Models\User;
use App\Repositories\PostRepository;
use Carbon\Carbon;
use Illuminate\Support\Arr;

class BatchEditPosts
{
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
     * @param PostRepository $posts
     * @return array
     */
    public function handle(PostRepository $posts)
    {
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

            if (isset($attributes['isDelete'])) {
                if ($this->actor->can('delete', $post)) {
                    if ($attributes['isDelete']) {
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

            $post->save();

            $result['data'][] = $post;
        }

        return $result;
    }
}
