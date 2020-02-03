<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Events\Post;

use App\Models\Post;
use App\Models\User;

class Revised
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
     * @param User|null $actor
     */
    public function __construct(Post $post, User $actor = null)
    {
        $this->post = $post;
        $this->actor = $actor;
    }
}
