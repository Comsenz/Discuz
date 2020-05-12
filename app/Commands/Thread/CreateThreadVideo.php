<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Commands\Thread;

use App\Models\Post;
use App\Models\Thread;
use App\Models\ThreadVideo;
use App\Models\User;
use App\Settings\SettingsRepository;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Auth\Exception\PermissionDeniedException;
use Discuz\Foundation\EventsDispatchTrait;
use Discuz\Qcloud\QcloudTrait;
use Illuminate\Contracts\Events\Dispatcher as EventDispatcher;
use Illuminate\Database\Eloquent\Model;
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
     * @var Thread|Post
     */
    public $model;

    /**
     * The attributes of the new thread.
     *
     * @var array
     */
    public $data;

    /**
     * @var
     */
    public $settings;

    /**
     * CreateThread constructor.
     * @param User $actor
     * @param Model $model
     * @param array $data
     */
    public function __construct(User $actor, Model $model, array $data)
    {
        $this->actor = $actor;
        $this->model = $model;
        $this->data = $data;
    }

    /**
     * @param EventDispatcher $events
     * @param SettingsRepository $settings
     * @return ThreadVideo
     * @throws PermissionDeniedException
     */
    public function handle(EventDispatcher $events, SettingsRepository $settings)
    {
        $this->events = $events;
        $this->settings = $settings;

        $fileId = Arr::get($this->data, 'attributes.file_id', '');

        /** @var ThreadVideo $threadVideo */
        $threadVideo = ThreadVideo::query()->where('file_id', $fileId)->firstOrNew();

        // 已关联主题的视频防止再次操作
        if ($threadVideo->thread_id != 0) {
            throw new PermissionDeniedException();
        }

        /**
         * 传入 Thread 时，则视为发视频
         * 传入 Post 时，则视为发音频
         * 如果改需求，建议改为构造方法中传入 type
         */
        if ($this->model instanceof Thread) {
            $thread = $this->model;
            $post = new Post;
            $type = ThreadVideo::TYPE_OF_VIDEO;
        } elseif ($this->model instanceof Post) {
            $thread = $this->model->thread;
            $post = $this->model;
            $type = ThreadVideo::TYPE_OF_AUDIO;
        } else {
            throw new PermissionDeniedException();
        }

        $threadVideo->user_id = $this->actor->id;
        $threadVideo->thread_id = $thread->id ?? 0;
        $threadVideo->post_id = $post->id ?? 0;
        $threadVideo->type = $type;
        $threadVideo->status = ThreadVideo::VIDEO_STATUS_TRANSCODING;
        $threadVideo->file_id = $fileId;
        $threadVideo->file_name = Arr::get($this->data, 'attributes.file_name', '');
        $threadVideo->media_url = Arr::get($this->data, 'attributes.media_url', '');
        $threadVideo->cover_url = Arr::get($this->data, 'attributes.cover_url', '');

        $threadVideo->save();

        if ($type === ThreadVideo::TYPE_OF_VIDEO && $thread->exists) {
            // 发布文章时，转码
            if ($this->settings->get('qcloud_vod_transcode_ads', 'qcloud')) {
                // 加密自适应
                $this->transcodeVideo($threadVideo->file_id, 'AdaptiveDynamicStreamingTaskSet');
            } else {
                // 普通转码
                $this->transcodeVideo($threadVideo->file_id, 'TranscodeTaskSet');
            }
            // 转动图
            if ($template_name = $this->settings->get('qcloud_vod_taskflow_gif', 'qcloud')) {
                $this->processMediaByProcedure($fileId, $template_name);
            }
        }

        return $threadVideo;
    }
}
