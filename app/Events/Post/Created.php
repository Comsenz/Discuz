<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: Created.php xxx 2019-10-16 18:23:00 LiuDongdong $
 */

namespace App\Events\Post;

use App\Models\Post;
use App\Models\User;

class Created
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
     * Created constructor.
     *
     * @param Post $post
     * @param User $actor
     */
    public function __construct(Post $post, User $actor = null)
    {
        $this->post = $post;
        $this->actor = $actor;
    }
}
