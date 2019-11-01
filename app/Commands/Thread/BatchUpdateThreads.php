<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: BatchUpdateThreads.php xxx 2019-10-21 14:22:00 LiuDongdong $
 */

namespace App\Commands\Thread;

use App\Models\Thread;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Arr;

class BatchUpdateThreads
{
    // use AssertPermissionTrait;

    /**
     * The ID array of the threads to update.
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
     * The attributes to update on the threads.
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

        if (isset($attributes['isSticky'])) {
            // TODO: 是否有权 置顶/取消置顶
            // $this->assertCan($actor, 'rename', $discussion);

            $update->put('is_sticky', $attributes['isSticky']);
        }

        if (isset($attributes['isEssence'])) {
            // TODO: 是否有权 加精/取消加精
            // $this->assertCan($actor, 'rename', $discussion);

            $update->put('is_essence', $attributes['isEssence']);
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

        $thread = new Thread;

        // 不更新 update_at
        $thread->timestamps = false;

        // 包括已软删除的模型
        return $thread->withTrashed()->whereIn('id', $this->ids)->update($update->all());
    }
}
