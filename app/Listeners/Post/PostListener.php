<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: PostListener.php xxx 2019-11-04 09:48:00 LiuDongdong $
 */

namespace App\Listeners\Post;

use App\Events\Post\Saving;
use Discuz\Api\Events\Serializing;
use Illuminate\Contracts\Events\Dispatcher;

class PostListener
{
    public function subscribe(Dispatcher $events)
    {
        // 喜欢帖子
        $events->listen(Serializing::class, AddPostLikeAttribute::class);
        $events->listen(Saving::class, SaveLikesToDatabase::class);
    }
}
