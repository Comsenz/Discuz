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
        if ($event->isSerializer(ThreadSerializer::class)) {
            $isFavorite = $event->model->favoriteUsers()->where('user_id', $event->actor->id)->first();

            $event->attributes['isFavorite'] = $isFavorite ? true : false;
        }
    }
}
