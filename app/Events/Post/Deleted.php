<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: Deleted.php xxx 2019-11-11 11:52:00 LiuDongdong $
 */

namespace App\Events\Post;

use App\Models\Post;
use App\Models\User;

class Deleted
{
    /**
     * @var Post
     */
    public $post;

    /**
     * @var User
     */
    public $actor;

    /**
     * @param Post $post
     * @param User $actor
     */
    public function __construct(Post $post, User $actor = null)
    {
        $this->post = $post;
        $this->actor = $actor;
    }
}
