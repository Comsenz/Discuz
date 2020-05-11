<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
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
