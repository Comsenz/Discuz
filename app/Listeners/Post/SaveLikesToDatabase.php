<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Listeners\Post;

use App\Events\Post\Deleted;
use App\Events\Post\Saving;
use App\MessageTemplate\Wechat\LikedMessage;
use App\MessageTemplate\Wechat\WechatLikedMessage;
use App\Notifications\Liked;
use Carbon\Carbon;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Auth\Exception\NotAuthenticatedException;
use Discuz\Auth\Exception\PermissionDeniedException;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Arr;

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

                    // 如果被点赞的用户不是当前用户，则通知被点赞的人
                    if ($post->user->id != $actor->id) {
                        $build = [
                            'message' => $post->content,
                            'raw' => Arr::only($post->toArray(), ['id', 'thread_id', 'is_first'])
                        ];
                        // 数据库通知
                        $post->user->notify(new Liked($post, $actor, LikedMessage::class, $build));

                        // 微信通知
                        // $post->user->notify(new Liked($post, $actor, WechatLikedMessage::class, $build));
                    }
                }
            }

            //刷新用户点赞数
            $actor->refreshUserLiked();
            $actor->save();
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
