<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: SaveLikesToDatabase.php xxx 2019-11-04 09:53:00 LiuDongdong $
 */

namespace App\Listeners\Post;

use App\Events\Post\Deleted;
use App\Events\Post\Saving;
use App\Notifications\Liked;
use Carbon\Carbon;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Auth\Exception\NotAuthenticatedException;
use Discuz\Auth\Exception\PermissionDeniedException;
use Illuminate\Contracts\Events\Dispatcher;

class SaveLikesToDatabase
{
    use AssertPermissionTrait;

    /**
     * @param Dispatcher $events
     */
    public function subscribe(Dispatcher $events)
    {
        $events->listen(Saving::class, [$this, 'whenPostIsSaving']);
        $events->listen(Deleted::class, [$this, 'whenPostIsDeleted']);
    }

    /**
     * @param Saving $event
     * @throws NotAuthenticatedException
     * @throws PermissionDeniedException
     */
    public function whenPostIsSaving(Saving $event)
    {
        $post = $event->post;
        $actor = $event->actor;
        $data = $event->data;

        $this->assertRegistered($actor);

        if ($post->exists && isset($data['attributes']['isLiked'])) {
            $this->assertCan($actor, 'like', $post);

            $isLiked = $actor->likedPosts()->where('post_id', $post->id)->exists();

            if ($isLiked) {
                // 已喜欢且 isLiked 为 false 时，取消喜欢
                if (!$data['attributes']['isLiked']) {
                    $actor->likedPosts()->detach($post->id);

                    $post->refreshLikeCount()->save();
                }
            } else {
                // 未喜欢且 isLiked 为 true 时，设为喜欢
                if ($data['attributes']['isLiked']) {
                    $actor->likedPosts()->attach($post->id, ['created_at' => Carbon::now()]);

                    $post->refreshLikeCount()->save();

                    // $post->raise(new PostWasLiked($post, $actor));
                    // 如果被点赞的用户不是当前用户，则通知被点赞的人
                    if ($post->user->id != $actor->id) {
                        $post->user->notify(new Liked($post));
                    }
                }
            }
        }
    }

    /**
     * @param Deleted $event
     */
    public function whenPostIsDeleted(Deleted $event)
    {
        $event->post->likedUsers()->detach();
    }
}
