<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: AddThreadFavoriteAttribute.php xxx 2019-10-29 16:23:00 LiuDongdong $
 */

namespace App\Listeners\Thread;

use App\Api\Serializer\ThreadSerializer;
use Discuz\Api\Events\Serializing;

class AddThreadFavoriteAttribute
{
    public function handle(Serializing $event)
    {
        if ($event->isSerializer(ThreadSerializer::class)
            && ($favoriteState = $event->model->favoriteState)) {
            $event->attributes['isFavorite'] = $favoriteState ? true : false;
            $event->attributes['favoriteAt'] = $event->formatDate($favoriteState->created_at);
        }
    }
}
