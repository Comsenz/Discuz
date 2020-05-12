<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Commands\Thread;

use App\Censor\Censor;
use App\Events\Category\CategoryRefreshCount;
use App\Events\Thread\Saving;
use App\Events\Thread\ThreadWasApproved;
use App\Events\Users\UserRefreshCount;
use App\Models\Thread;
use App\Models\ThreadVideo;
use App\Models\User;
use App\Repositories\ThreadRepository;
use App\Repositories\ThreadVideoRepository;
use App\Traits\ThreadNoticesTrait;
use App\Validators\ThreadValidator;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Auth\Exception\PermissionDeniedException;
use Discuz\Foundation\EventsDispatchTrait;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;

class EditThread
{
    use AssertPermissionTrait;
    use EventsDispatchTrait;
    use ThreadNoticesTrait;

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
     * The attributes to update on the thread.
     *
     * @var array
     */
    public $data;

    /**
     * @param int $threadId The ID of the thread to edit.
     * @param User $actor The user performing the action.
     * @param array $data The attributes to update on the thread.
     */
    public function __construct($threadId, User $actor, array $data)
    {
        $this->threadId = $threadId;
        $this->actor = $actor;
        $this->data = $data;
    }

    /**
     * @param Dispatcher $events
     * @param ThreadRepository $threads
     * @param Censor $censor
     * @param ThreadValidator $validator
     * @param ThreadVideoRepository $threadVideos
     * @return Thread
     * @throws PermissionDeniedException
     * @throws ValidationException
     */
    public function handle(Dispatcher $events, ThreadRepository $threads, Censor $censor, ThreadValidator $validator, ThreadVideoRepository $threadVideos)
    {
        $this->events = $events;

        $attributes = Arr::get($this->data, 'attributes', []);

        $thread = $threads->findOrFail($this->threadId, $this->actor);

        if (isset($attributes['title'])) {
            $this->assertCan($this->actor, 'rename', $thread);

            // 敏感词校验
            $title = $censor->checkText($attributes['title']);

            // 存在审核敏感词时，将主题放入待审核
            if ($censor->isMod) {
                $thread->is_approved = 0;
            }

            $thread->title = $title;
        } else {
            // 不修改标题时，不更新修改时间
            $thread->timestamps = false;
        }

        //长文、视频可以修改价格
        if (isset($attributes['price']) && ($thread->type != 0)) {
            $this->assertCan($this->actor, 'editPrice', $thread);

            $thread->price = (float) $attributes['price'];
        }

        if (isset($attributes['isApproved']) && $attributes['isApproved'] < 3) {
            $this->assertCan($this->actor, 'approve', $thread);
            if ($thread->is_approved != $attributes['isApproved']) {
                $thread->is_approved = $attributes['isApproved'];
                $approvedMsg = isset($attributes['message']) ? $attributes['message'] : '';

                // 内容审核通知
                $thread->raise(new ThreadWasApproved(
                    $thread,
                    $this->actor,
                    ['notice_type' => 'isApproved', 'message' => $approvedMsg]
                ));
            }
        }

        if (isset($attributes['isSticky'])) {
            $this->assertCan($this->actor, 'sticky', $thread);
            if ($thread->is_sticky != $attributes['isSticky']) {
                $thread->is_sticky = $attributes['isSticky'];
                // 置顶后 通知发帖人置顶消息
                if ($attributes['isSticky']) {
                    // 内容置顶通知
                    $thread->raise(new ThreadWasApproved(
                        $thread,
                        $this->actor,
                        ['notice_type' => 'isSticky']
                    ));
                }
            }
        }

        if (isset($attributes['isEssence'])) {
            $this->assertCan($this->actor, 'essence', $thread);
            if ($thread->is_essence != $attributes['isEssence']) {
                $thread->is_essence = $attributes['isEssence'];
                // 内容精华通知
                if ($attributes['isEssence']) {
                    $thread->raise(new ThreadWasApproved(
                        $thread,
                        $this->actor,
                        ['notice_type' => 'isEssence']
                    ));
                }
            }
        }

        if (isset($attributes['isDeleted'])) {
            $this->assertCan($this->actor, 'hide', $thread);
            if ((bool) $thread->deleted_at != $attributes['isDeleted']) {
                $message = isset($attributes['message']) ? $attributes['message'] : '';

                if ($attributes['isDeleted']) {
                    // 内容删除通知
                    $thread->hide($this->actor, ['message' => $message]);
                } else {
                    $thread->restore($this->actor, ['message' => $message]);
                }
            }
        }

        // 原分类ID
        $cateId = $thread->category_id;

        $this->events->dispatch(
            new Saving($thread, $this->actor, $this->data)
        );

        $type = $thread->type;
        $validAttr = $thread->getDirty() + compact('type');
        //视频贴验证是否上传视频
        $file_id = Arr::get($this->data, 'attributes.file_id');
        $file_name = Arr::get($this->data, 'attributes.file_name');

        if ($file_id !== null || $file_name !== null) {
            $validAttr += compact('file_id', 'file_name');
        }
        $validator->valid($validAttr);

        //编辑视频
        if ($thread->type == 2 && $file_id) {
            /** @var ThreadVideo $threadVideo */
            $threadVideo = $threadVideos->findOrFailByThreadId($thread->id);

            if ($threadVideo->file_id != $attributes['file_id']) {
                // 将旧的视频主题 id 设为 0
                $threadVideo->thread_id = 0;
                $threadVideo->save();

                // 创建新的视频记录
                $newVideo = new ThreadVideo;

                $newVideo->thread_id = $thread->id;
                $newVideo->post_id = 0;
                $newVideo->user_id = $this->actor->id;
                $newVideo->type = ThreadVideo::TYPE_OF_VIDEO;
                $newVideo->status = ThreadVideo::VIDEO_STATUS_TRANSCODING;
                $newVideo->file_name = $attributes['file_name'];
                $newVideo->file_id = $attributes['file_id'];
                $newVideo->media_url = '';
                $newVideo->cover_url = '';

                $newVideo->save();

                // 重新上传视频修改为审核状态
                $thread->is_approved = 0;
            }
        }

        $thread->save();

        $this->dispatchEventsFor($thread, $this->actor);

        /**
         * 更改统计数
         */
        $this->events->dispatch(
            new UserRefreshCount($thread->user)
        );
        $this->events->dispatch(
            new CategoryRefreshCount($thread->category, $cateId)
        );

        return $thread;
    }
}
