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
use App\Repositories\ThreadRepository;
use App\Validators\ThreadValidator;
use Discuz\Foundation\EventsDispatchTrait;
use Illuminate\Contracts\Events\Dispatcher as EventDispatcher;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;

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
     * @param array $data The attributes to update on the discussion.
     */
    public function __construct($threadId, User $actor, array $data)
    {
        $this->threadId = $threadId;
        $this->actor = $actor;
        $this->data = $data;
    }

    /**
     * @param EventDispatcher $events
     * @param ThreadRepository $thread
     * @param ThreadValidator $validator
     * @return Thread|Builder|Model
     * @throws ValidationException
     */
    public function handle(EventDispatcher $events, ThreadRepository $thread, ThreadValidator $validator)
    {
        $this->events = $events;
        $attributes = Arr::get($this->data, 'attributes', []);

        // TODO: 权限验证 是否有权查看
        // $this->assertCan($this->actor, 'startDiscussion');

        // 数据验证
        $validator->valid($this->data);

        $thread = $thread->findOrFail($this->threadId, $this->actor);

        if (isset($attributes['title'])) {
            // TODO: 是否有权修改标题
            // $this->assertCan($actor, 'rename', $discussion);

            $thread->title = $attributes['title'];
        }

        if (isset($attributes['isApproved'])) {
            // TODO: 是否有权 审核/放入待审核
            // $this->assertCan($actor, 'rename', $discussion);

            $thread->is_approved = $attributes['isApproved'];
        }

        if (isset($attributes['isSticky'])) {
            // TODO: 是否有权 置顶/取消置顶
            // $this->assertCan($actor, 'rename', $discussion);

            $thread->is_sticky = $attributes['isSticky'];
        }

        if (isset($attributes['isEssence'])) {
            // TODO: 是否有权 加精/取消加精
            // $this->assertCan($actor, 'rename', $discussion);

            $thread->is_essence = $attributes['isEssence'];
        }

        if (isset($attributes['isDelete'])) {
            // TODO: 是否有权删除
            // $this->assertCan($actor, 'hide', $discussion);

            if ($attributes['isDelete']) {
                $thread->deleted_user_id = $this->actor->id;
                $thread->delete();
            } else {
                $thread->deleted_user_id = null;
                $thread->restore();
            }
        }

        $this->events->dispatch(
            new Saving($thread, $this->actor, $this->data)
        );

        $thread->save();

        $this->dispatchEventsFor($thread, $this->actor);

        return $thread;
    }
}
