<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: BatchDeleteThread.php xxx 2019-10-21 14:22:00 LiuDongdong $
 */

namespace App\Commands\Thread;

use App\Models\Post;
use App\Models\Thread;
use App\Models\User;
use Illuminate\Support\Collection;

class BatchDeleteThread
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
     * @return int
     */
    public function handle()
    {
        // TODO: 权限验证
        // $this->assertCan($this->actor, 'startDiscussion');

        $ids = explode(',', $this->data->get('ids'));

        // 删除相关主题下的所有回复
        Post::whereIn('thread_id', $ids)->forceDelete();

        // 删除主题
        return Thread::whereIn('id', $ids)->forceDelete();
    }
}
