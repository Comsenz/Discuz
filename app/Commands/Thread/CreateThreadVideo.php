<?php

/**
 * Copyright (C) 2020 Tencent Cloud.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace App\Commands\Thread;

use App\Models\Thread;
use App\Models\ThreadVideo;
use App\Models\User;
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

    /**
     * @var User
     */
    public $actor;

    /**
     * @var Thread
     */
    public $thread;

    /**
     * @var int
     */
    public $type;

    /**
     * @var array
     */
    public $data;

    /**
     * @var SettingsRepository
     */
    public $settings;

    /**
     * @param User $actor
     * @param Thread $thread
     * @param int $type
     * @param array $data
     */
    public function __construct(User $actor, Thread $thread, int $type, array $data)
    {
        $this->actor = $actor;
        $this->thread = $thread;
        $this->type = $type;
        $this->data = $data;
    }

    /**
     * @param EventDispatcher $events
     * @param SettingsRepository $settings
     * @return ThreadVideo
     */
    public function handle(EventDispatcher $events, SettingsRepository $settings)
    {
        $this->events = $events;
        $this->settings = $settings;

        $attributes = Arr::get($this->data, 'attributes', []);

        $fileId = Arr::get($attributes, 'file_id', '');

        /** @var ThreadVideo $threadVideo */
        $threadVideo = ThreadVideo::query()->where('thread_id', 0)->where('file_id', $fileId)->firstOrNew();

        $threadVideo->user_id = $this->actor->id;
        $threadVideo->thread_id = $this->thread->id ?? 0;
        $threadVideo->post_id = 0;  // 暂时用不到了
        $threadVideo->type = $this->type;
        $threadVideo->file_id = $fileId;
        $threadVideo->file_name = Arr::get($attributes, 'file_name', $threadVideo->file_name ?? '');
        $threadVideo->media_url = Arr::get($attributes, 'media_url', $threadVideo->media_url ?? '');
        $threadVideo->cover_url = Arr::get($attributes, 'cover_url', $threadVideo->cover_url ?? '');

        // 视频转码中，音频无需转码
        if ($threadVideo->type === ThreadVideo::TYPE_OF_VIDEO) {
            $threadVideo->status = ThreadVideo::VIDEO_STATUS_TRANSCODING;
        } else {
            $threadVideo->status = ThreadVideo::VIDEO_STATUS_SUCCESS;
        }

        $threadVideo->save();

        if ($threadVideo->type === ThreadVideo::TYPE_OF_VIDEO && $this->thread->exists) {
            // 发布文章时，转码
            $this->transcodeVideo($threadVideo->file_id, 'TranscodeTaskSet');

            // 转动图
            if ($template_name = $this->settings->get('qcloud_vod_taskflow_gif', 'qcloud')) {
                $this->processMediaByProcedure($fileId, $template_name);
            }
        }

        return $threadVideo;
    }
}
