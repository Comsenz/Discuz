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
            $event->attributes['canLike'] = (bool) $event->actor->can('like', $event->model);

            if ($likeState = $event->model->likeState) {
                $event->attributes['isLiked'] = $likeState ? true : false;
                $event->attributes['likedAt'] = $event->formatDate($likeState->created_at);
            }
        }
    }
}
