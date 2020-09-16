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

use App\Commands\Attachment\DeleteAttachment;
use App\Events\Post\Saved;
use App\Events\Post\Saving;
use App\Models\Attachment;
use App\Models\Post;
use App\Models\Thread;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Auth\Exception\PermissionDeniedException;
use Exception;
use Illuminate\Contracts\Bus\Dispatcher as BusDispatcher;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

class PostAttachment
{
    use AssertPermissionTrait;

    /**
     * @var BusDispatcher
     */
    protected $bus;

    /**
     * @param BusDispatcher $bus
     */
    public function __construct(BusDispatcher $bus)
    {
        $this->bus = $bus;
    }

    /**
     * @param Dispatcher $events
     */
    public function subscribe(Dispatcher $events)
    {
        $events->listen(Saving::class, [$this, 'whenPostIsSaving']);
        $events->listen(Saved::class, [$this, 'whenPostWasSaved']);
    }

    /**
     * @param Saving $event
     * @throws Exception
     */
    public function whenPostIsSaving(Saving $event)
    {
        // 图片帖必须有图片
        if (
            $event->post->is_first
            && Arr::has($event->data, 'relationships.attachments.data')
            && ! array_column(Arr::get($event->data, 'relationships.attachments.data'), 'id')
            && $event->post->thread->type === Thread::TYPE_OF_IMAGE
        ) {
            throw new Exception('cannot_create_image_thread_without_attachments');
        }
    }

    /**
     * @param Saved $event
     * @throws PermissionDeniedException
     */
    public function whenPostWasSaved(Saved $event)
    {
        $post = $event->post;
        $actor = $event->actor;

        // 请求中未传附件不处理
        if (! Arr::has($event->data, 'relationships.attachments.data')) {
            return;
        }

        // 请求中的附件，修改帖子附件时，传要保留的附件及新的附件，未保留的将被删除
        $ids = array_column(Arr::get($event->data, 'relationships.attachments.data'), 'id');

        if ($post->wasRecentlyCreated) {
            // 未绑定的的附件
            $attachments = Attachment::query()
                ->where('user_id', $actor->id)
                ->where('type_id', 0)
                ->whereIn('id', $ids)
                ->get();
        } else {
            // 是否有权编辑
            $this->assertCan($actor, 'edit', $post);

            // 已绑定的附件和未绑定的的附件
            $attachments = Attachment::query()
                ->where('type_id', $post->id)
                ->orWhere(function (Builder $query) use ($actor, $ids) {
                    $query->where('user_id', $actor->id)
                          ->where('type_id', 0)
                          ->whereIn('id', $ids);
                })->get();

            // 删除请求中不存在的附件
            $attachments->whereNotIn('id', $ids)->each(function (Attachment $attachment) use ($actor) {
                $this->bus->dispatch(
                    new DeleteAttachment($attachment->id, $actor)
                );
            });
        }

        // 存在非法附件，帖子设为非法
        if ($attachments->contains('is_approved', Attachment::UNAPPROVED)) {
            $post->is_approved = Post::UNAPPROVED;

            $post->save();
        }

        // 绑定新附件
        Attachment::query()
            ->where('user_id', $actor->id)
            ->where('type_id', 0)
            ->whereIn('id', $ids)
            ->update(['type_id' => $post->id]);
    }
}
