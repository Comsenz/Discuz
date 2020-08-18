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

namespace App\Traits;

use App\BlockEditor\Formater\PaidCheck;
use App\Models\Attachment;
use App\Models\Post;
use App\Models\Thread;
use App\Models\ThreadVideo;
use App\Models\User;
use Illuminate\Support\Str;
use App\BlockEditor\Formater\PostFormater;

/**
 * @package App\Traits
 */
trait HasPaidContent
{
    /**
     * @var User
     */
    protected $actor;

    /**
     * @var array
     */
    protected $threads = [];

    /**
     * @var array
     */
    protected $orders = [];

    /**
     * @param Thread|Post|Attachment|ThreadVideo $model
     */
    public function paidContent($model)
    {
        Thread::setStateUser($this->actor);

        // 作者本人 或 管理员 不处理（新增类型时请保证 $model->user_id 存在）
        if ($this->actor->id === $model->user_id || $this->actor->isAdmin()) {
            if ($model instanceof Post) {
                // 过滤块，处理未付费可见内容
                $model->content = PostFormater::pure($model);
            }
            return;
        }


        if ($model instanceof Post) {
            $model->content = PostFormater::pure($model);
        } elseif ($model instanceof Attachment) {
            $model = PostFormater::checkAttachment($model);
            $status = PaidCheck::isPaid($model->type_id, $model->pay_blocks);
            if ($status) {
                $model->setAttribute('paid', true);
            } else {
                $this->blurImage($model);
                $model->setAttribute('paid', false);
            }
        } elseif ($model instanceof ThreadVideo) {
            $model = PostFormater::checkVideo($model);
            $status = PaidCheck::isPaid($model->post_id, $model->pay_blocks);
            if ($status) {
                $model->setAttribute('paid', true);
            } else {
                $model->file_id = '';
                $model->media_url = '';
                $model->setAttribute('paid', false);
            }
        }
    }

    /**
     * 付费图片帖未付费时返回模糊图
     *
     * @param Attachment $attachment
     */
    public function blurImage(Attachment $attachment)
    {
        if (
            $attachment->type === Attachment::TYPE_OF_IMAGE
        ) {
            $attachment->setAttribute('blur', true);

            $parts = explode('.', $attachment->attachment);
            $parts[0] = md5($parts[0]);

            $attachment->attachment = implode('_blur.', $parts);
        }
    }


    /**
     * @TODO 编辑器 付费主题处理
     * 是否无权查看
     *
     * @param Thread $thread
     * @return bool
     */
    public function cannotView(Thread $thread)
    {
        return ! $this->actor->hasPermission('thread.viewPosts')
            || ($thread->price > 0 && ! $thread->is_paid);
    }
}
