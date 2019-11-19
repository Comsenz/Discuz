<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: AddPostLikeAttribute.php xxx 2019-11-04 09:53:00 LiuDongdong $
 */

namespace App\Listeners\Post;

use App\Api\Serializer\PostSerializer;
use Discuz\Api\Events\Serializing;

class AddPostLikeAttribute
{
    public function handle(Serializing $event)
    {
        if ($event->isSerializer(PostSerializer::class)) {
            // 是否可以喜欢
            $event->attributes['canLike'] = (bool) $event->actor->can('like', $event->model);

            // 是否喜欢
            $isLiked = $event->model->likedUsers()->where('user_id', $event->actor->id)->first();

            if ($isLiked) {
                $event->attributes['isLiked'] = $isLiked ? true : false;
                $event->attributes['likedAt'] = $event->formatDate($isLiked->created_at);
            }
        }
    }
}
