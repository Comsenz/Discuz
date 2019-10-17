<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: Threads.php xxx 2019-10-09 20:14:00 LiuDongdong $
 */

namespace App\Models;

use Carbon\Carbon;
use Discuz\Foundation\EventGeneratorTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $user_id
 * @property int $last_posted_user_id
 * @property string $title
 * @property float $price
 * @property int $reply_count
 * @property int $view_count
 * @property int $like_count
 * @property int $favorite_count
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 * @property int $delete_user_id
 * @property bool $is_approved
 * @property bool $is_sticky
 * @property bool $is_essence
 * @package App\Models
 */
class Thread extends Model
{
    use EventGeneratorTrait;
    use SoftDeletes;

    /**
     * Set the discussion's last post details.
     *
     * @param Post $post
     * @return $this
     */
    public function setLastPost(Post $post)
    {
        $this->last_posted_user_id = $post->user_id;
        $this->updated_at = $post->created_at;

        return $this;
    }
}
