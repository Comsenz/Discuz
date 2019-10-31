<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: BatchUpdatePosts.php xxx 2019-10-31 11:48:00 LiuDongdong $
 */

namespace App\Commands\Post;

use App\Models\Post;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Arr;

class BatchUpdatePosts
{
    // use AssertPermissionTrait;

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
     * @return int
     */
    public function handle()
    {
        // TODO: 权限验证（是否有权查看）
        // $this->assertCan($this->actor, 'startDiscussion');

        $attributes = Arr::get($this->data, 'attributes', []);
        $update = collect();

        if (isset($attributes['isApproved'])) {
            // TODO: 是否有权 审核/放入待审核
            // $this->assertCan($actor, 'rename', $discussion);

            $update->put('is_approved', $attributes['isApproved']);
        }

        if (isset($attributes['isDelete'])) {
            // TODO: 是否有权删除
            // $this->assertCan($actor, 'hide', $discussion);

            if ($attributes['isDelete']) {
                $update->put('deleted_at', Carbon::now());
                $update->put('deleted_user_id', $this->actor->id);
            } else {
                $update->put('deleted_at', null);
                $update->put('deleted_user_id', null);
            }
        }

        $post = new Post;

        // 不更新 update_at
        $post->timestamps = false;

        // 包括已软删除的模型
        return $post->withTrashed()->whereIn('id', $this->ids)->update($update->all());
    }
}
