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

namespace App\Listeners\Thread;

use App\Api\Serializer\ThreadSerializer;
use Discuz\Api\Events\Serializing;

class AddThreadFavoriteAttribute
{
    public function handle(Serializing $event)
    {
        if ($event->isSerializer(ThreadSerializer::class)) {
            $event->attributes['canFavorite'] = (bool) $event->actor->can('favorite', $event->model);

            if ($favoriteState = $event->model->favoriteState) {
                $event->attributes['isFavorite'] = true;
                $event->attributes['favoriteAt'] = $event->formatDate($favoriteState->created_at);
            } else {
                $event->attributes['isFavorite'] = false;
            }
        }
    }
}
