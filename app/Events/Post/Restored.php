<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: Restored.php xxx 2019-11-28 10:31:00 LiuDongdong $
 */

namespace App\Events\Post;

use App\Models\Post;
use App\Models\User;

class Restored
{
    /**
     * The post that was restored.
     *
     * @var Post
     */
    public $post;

    /**
     * @var User
     */
    public $actor;

    /**
     * @var array
     */
    public $data;

    /**
     * @param Post $post
     * @param User $actor
     * @param array $data
     */
    public function __construct(Post $post, User $actor, array $data = [])
    {
        $this->post = $post;
        $this->actor = $actor;
        $this->data = $data;
    }
}
