<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: SaveLikesToDatabase.php xxx 2019-11-04 09:53:00 LiuDongdong $
 */

namespace App\Listeners\Post;

use App\Events\Post\Saving;
use Carbon\Carbon;
use Discuz\Auth\AssertPermissionTrait;

class SaveLikesToDatabase
{
    use AssertPermissionTrait;

    public function handle(Saving $event)
    {
        $post = $event->post;
        $actor = $event->actor;
        $data = $event->data;

        $this->assertRegistered($actor);

        if ($post->exists && isset($data['attributes']['isLiked'])) {
            // $this->assertCan($actor, 'like', $post);

            $isLiked = $actor->likedPosts()->withTrashed()->where('post_id', $post->id)->exists();

            if ($isLiked) {
                // 已喜欢且 isLiked 为 false 时，取消喜欢
                if (!$data['attributes']['isLiked']) {
                    $actor->likedPosts()->detach($post->id);
                }
            } else {
                // 未喜欢且 isLiked 为 true 时，设为喜欢
                if ($data['attributes']['isLiked']) {
                    $actor->likedPosts()->attach($post->id, ['created_at' => Carbon::now()]);
                }
            }
        }
    }
}
