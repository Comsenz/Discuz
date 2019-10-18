<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: EditThread.php xxx 2019-10-17 17:44:00 LiuDongdong $
 */

namespace App\Commands\Thread;

use App\Events\Thread\Saving;
use App\Models\Thread;
use App\Models\User;
use App\Validators\ThreadValidator;
use Discuz\Foundation\EventsDispatchTrait;
use Illuminate\Contracts\Events\Dispatcher as EventDispatcher;
use Illuminate\Support\Collection;

class EditThread
{
    use EventsDispatchTrait;

    /**
     * The ID of the thread to edit.
     *
     * @var int
     */
    public $threadId;

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
     * @param int $threadId The ID of the discussion to edit.
     * @param User $actor The user performing the action.
     * @param Collection $data The attributes to update on the discussion.
     */
    public function __construct($threadId, $actor, Collection $data)
    {
        // TODO: User $actor
        $this->threadId = $threadId;
        $this->actor = $actor;
        $this->data = $data;
    }


    public function handle(EventDispatcher $events, ThreadValidator $validator)
    {
        $this->events = $events;

        // TODO: 权限验证 是否有权查看
        // $this->assertCan($this->actor, 'startDiscussion');

        // 数据验证
        $validator->valid($this->data->all());

        $thread = Thread::findOrFail($this->threadId);

        if ($this->data->has('title')) {
            // TODO: 是否有权重命名
            // $this->assertCan($actor, 'rename', $discussion);

            $thread->title = $this->data->get('title');
        }

        if ($this->data->has('isApproved')) {
            // TODO: 是否有权 审核/放入待审核
            // $this->assertCan($actor, 'rename', $discussion);

            $thread->is_approved = $this->data->get('isApproved') ? 1 : 0;
        }

        if ($this->data->has('isSticky')) {
            // TODO: 是否有权 置顶/取消置顶
            // $this->assertCan($actor, 'rename', $discussion);

            $thread->is_sticky = $this->data->get('isSticky') ? 1 : 0;
        }

        if ($this->data->has('isEssence')) {
            // TODO: 是否有权 加精/取消加精
            // $this->assertCan($actor, 'rename', $discussion);

            $thread->is_essence = $this->data->get('isEssence') ? 1 : 0;
        }

        if ($this->data->has('isDelete')) {
            // TODO: 是否有权删除
            // $this->assertCan($actor, 'hide', $discussion);

            if ($this->data->get('isHidden')) {
                $thread->delete_user_id = null;
                $thread->delete();
            } else {
                $thread->delete_user_id = null;
                $thread->restore();
            }
        }

        $this->events->dispatch(
            new Saving($thread, $this->actor, $this->data->all())
        );

        $thread->save();

        $this->dispatchEventsFor($thread, $this->actor);

        return $thread;
    }
}
