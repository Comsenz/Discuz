<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: BatchUpdateThread.php xxx 2019-10-21 14:22:00 LiuDongdong $
 */

namespace App\Commands\Thread;

use App\Models\Thread;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class BatchUpdateThread
{
    /**
     * The user performing the action.
     *
     * @var User
     */
    public $actor;

    /**
     * The attributes of the new thread.
     *
     * @var array
     */
    public $data;

    /**
     * The current ip address of the actor.
     *
     * @var array
     */
    public $ip;

    /**
     * CreateThread constructor.
     * @param User $actor
     * @param Collection $data
     * @param $ip
     */
    public function __construct($actor, Collection $data, $ip)
    {
        // TODO: User $actor
        $this->actor = $actor;
        $this->data = $data;
        $this->ip = $ip;
    }

    /**
     * @return Thread
     */
    public function handle()
    {
        // TODO: 权限验证
        // $this->assertCan($this->actor, 'startDiscussion');

        $ids = explode(',', $this->data->get('ids'));
        $update = collect();

        if ($this->data->has('isApproved')) {
            // TODO: 是否有权 审核/放入待审核
            // $this->assertCan($actor, 'rename', $discussion);

            $update->put('is_approved', $this->data->get('isApproved'));
        }

        if ($this->data->has('isSticky')) {
            // TODO: 是否有权 置顶/取消置顶
            // $this->assertCan($actor, 'rename', $discussion);

            $update->put('is_sticky', $this->data->get('isSticky'));
        }

        if ($this->data->has('isEssence')) {
            // TODO: 是否有权 加精/取消加精
            // $this->assertCan($actor, 'rename', $discussion);

            $update->put('is_essence', $this->data->get('isEssence'));
        }

        if ($this->data->has('isDelete')) {
            // TODO: 是否有权删除
            // $this->assertCan($actor, 'hide', $discussion);

            if ($this->data->get('isDelete')) {
                $update->put('deleted_at', Carbon::now());
                $update->put('deleted_user_id', $this->actor->id);
            } else {
                $update->put('deleted_at', null);
                $update->put('deleted_user_id', null);
            }
        }

        return Thread::whereIn('id', $ids)->update($update->all());
    }
}
