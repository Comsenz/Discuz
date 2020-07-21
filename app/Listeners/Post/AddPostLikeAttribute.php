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

use App\Api\Serializer\BasicPostSerializer;
use Discuz\Api\Events\Serializing;

class AddPostLikeAttribute
{
    public function handle(Serializing $event)
    {
        if ($event->isSerializer(BasicPostSerializer::class)) {
            $event->attributes['canLike'] = (bool) $event->actor->can('like', $event->model);

            if ($likeState = $event->model->likeState) {
                $event->attributes['isLiked'] = true;
                $event->attributes['likedAt'] = $event->formatDate($likeState->created_at);
            } else {
                $event->attributes['isLiked'] = false;
            }
        }
    }
}
