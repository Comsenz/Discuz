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

use App\Events\Thread\Saving;
use App\Models\ThreadUser;
use Carbon\Carbon;
use Discuz\Auth\AssertPermissionTrait;

class SaveFavoriteToDatabase
{
    use AssertPermissionTrait;

    public function handle(Saving $event)
    {
        $thread = $event->thread;
        $actor = $event->actor;
        $data = $event->data;

        $this->assertRegistered($actor);

        if ($thread->exists && isset($data['attributes']['isFavorite'])) {
            $this->assertCan($actor, 'favorite', $thread);

            $isFavorite = ThreadUser::query()->where('thread_id', $thread->id)->where('user_id', $actor->id)->exists();

            if ($isFavorite) {
                // 已收藏且 isFavorite 为 false 时，取消收藏
                if (!$data['attributes']['isFavorite']) {
                    $actor->favoriteThreads()->detach($thread->id);
                }
            } else {
                // 未收藏且 isFavorite 为 true 时，添加收藏
                if ($data['attributes']['isFavorite']) {
                    $actor->favoriteThreads()->attach($thread->id, ['created_at' => Carbon::now()]);
                }
            }
        }
    }
}
