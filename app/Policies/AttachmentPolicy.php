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

namespace App\Policies;

use App\Models\Attachment;
use App\Models\User;
use Discuz\Foundation\AbstractPolicy;
use Illuminate\Database\Eloquent\Builder;

class AttachmentPolicy extends AbstractPolicy
{
    /**
     * {@inheritdoc}
     */
    protected $model = Attachment::class;

    /**
     * @param User $actor
     * @param string $ability
     * @return bool|null
     */
    public function can(User $actor, $ability)
    {
        if ($actor->hasPermission('attachment.' . $ability)) {
            return true;
        }
    }

    /**
     * @param User $actor
     * @param Builder $query
     */
    public function find(User $actor, Builder $query)
    {

    }

    /**
     * @param User $actor
     * @param Attachment $attachment
     * @return bool|null
     */
    public function delete(User $actor, Attachment $attachment)
    {
        if ($attachment->user_id == $actor->id || $actor->isAdmin()) {
            return true;
        }

        // 有权编辑帖子时，允许删除帖子下的附件
        $postAttachmentTypes = [
            Attachment::TYPE_OF_FILE,
            Attachment::TYPE_OF_IMAGE,
            Attachment::TYPE_OF_AUDIO,
            Attachment::TYPE_OF_VIDEO,
        ];

        if (in_array($attachment->type, $postAttachmentTypes) && $actor->can('edit', $attachment->post)) {
            return true;
        }
    }
}
