<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Listeners\Thread;

use App\Events\Thread\Saving;
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

            $isFavorite = $actor->favoriteThreads()->where('thread_id', $thread->id)->exists();

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
