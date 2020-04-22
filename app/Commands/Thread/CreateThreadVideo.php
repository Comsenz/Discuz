<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Commands\Thread;

use App\Models\ThreadVideo;
use App\Models\User;
use App\Repositories\ThreadRepository;
use App\Settings\SettingsRepository;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Foundation\EventsDispatchTrait;
use Discuz\Qcloud\QcloudTrait;
use Illuminate\Contracts\Events\Dispatcher as EventDispatcher;
use Illuminate\Support\Arr;

class CreateThreadVideo
{
    use AssertPermissionTrait;
    use EventsDispatchTrait;
    use QcloudTrait;

    const API_URL = 'vod.tencentcloudapi.com';

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
     * The id of the thread.
     *
     * @var int
     */
    public $threadId;

    /**
     * CreateThread constructor.
     * @param User $actor
     * @param $threadId
     * @param array $data
     */
    public function __construct(User $actor, $threadId, array $data)
    {
        $this->actor = $actor;
        $this->data = $data;
        $this->threadId = $threadId;
    }

    /**
     * @param EventDispatcher $events
     * @param ThreadRepository $threads
     * @param ThreadVideo $threadVideo
     * @return ThreadVideo
     */
    public function handle(EventDispatcher $events, ThreadRepository $threads, ThreadVideo $threadVideo)
    {
        $this->events = $events;

        //传入主题ID时更新数据
        if ($this->threadId) {
            $thread = $threads->findOrFail($this->threadId);
        }
        $file_id = Arr::get($this->data, 'attributes.file_id');
        $threadVideoRes = $threadVideo->where('file_id', $file_id)->first();
        if ($threadVideoRes) {
            $threadVideo = $threadVideoRes;
        }

        $threadVideo->user_id   = $this->actor->id;
        $threadVideo->thread_id = isset($thread) ? $thread->id : $this->threadId;
        $threadVideo->status    = $threadVideo::VIDEO_STATUS_TRANSCODING;
        $threadVideo->file_name = Arr::get($this->data, 'attributes.file_name')?:'';
        $threadVideo->file_id   = $file_id;
        $threadVideo->media_url = Arr::get($this->data, 'attributes.media_url')?:'';
        $threadVideo->cover_url = Arr::get($this->data, 'attributes.cover_url')?:'';

        $threadVideo->save();

        if (isset($thread)) {
            //发布文章时，通知腾讯云点播转码
            $this->transcodeVideo($threadVideo->file_id);
        }

        return $threadVideo;
    }
}
