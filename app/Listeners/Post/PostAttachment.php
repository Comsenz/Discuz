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

namespace App\Listeners\Post;

use App\Events\Post\Saving;
use App\Models\Attachment;
use Discuz\Auth\AssertPermissionTrait;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Arr;

class PostAttachment
{
    use AssertPermissionTrait;

    /**
     * @param Dispatcher $events
     */
    public function subscribe(Dispatcher $events)
    {
        $events->listen(Saving::class, [$this, 'whenPostIsSaving']);
    }

    /**
     * @param Saving $event
     * @throws \Exception
     */
    public function whenPostIsSaving(Saving $event)
    {
        $post = $event->post;
        $attachments = (array) Arr::get($event->data, 'relationships.attachments.data');

        switch ($event->post->thread->type) {
            // // 文本
            // case 0:
            //     // 文字帖不能有附件
            //     if ($attachments) {
            //         throw new \Exception('cannot_create_text_thread_with_attachments');
            //     }
            //
            //     break;
            // // 帖子
            // case 1:
            // // 视频
            // case 2:
            // 图片
            case 3:
                // 发表图片帖必须有图片
                if (! $post->exists && $post->is_first) {
                    $images = $attachments && Attachment::query()
                            ->where('user_id', $event->actor->id)
                            ->where('type', Attachment::TYPE_OF_IMAGE)
                            ->where('type_id', 0)
                            ->whereIn('id', array_column($attachments, 'id'))
                            ->exists();

                    if (! $images) {
                        throw new \Exception('cannot_create_image_thread_without_attachments');
                    }
                }

                break;
        }
    }
}
