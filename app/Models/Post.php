<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: Post.php xxx 2019-10-08 16:21 LiuDongdong $
 */

namespace App\Models;

use App\Events\Post\Created;
use Carbon\Carbon;
use Discuz\Foundation\EventGeneratorTrait;
use Discuz\Database\ScopeVisibilityTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $user_id
 * @property int $thread_id
 * @property int $reply_id
 * @property string $content
 * @property string $ip
 * @property int $comment_count
 * @property int $like_count
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 * @property int $delete_user_id
 * @property bool $is_first
 * @property bool $is_approved
 * @property Thread $thread
 * @package App\Models
 */
class Post extends Model
{
    use EventGeneratorTrait;
    use ScopeVisibilityTrait;
    use SoftDeletes;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_first' => 'boolean',
        'is_approved' => 'boolean',
    ];

    /**
     * Create a new instance in reply to a thread.
     *
     * @param $threadId
     * @param string $content
     * @param int $userId
     * @param $ip
     * @param $replyId
     * @param int $isFirst
     * @return static
     */
    public static function reply($threadId, $content, $userId, $ip, $replyId, $isFirst = 0)
    {
        $post = new static;

        $post->created_at = Carbon::now();
        $post->thread_id = $threadId;
        $post->user_id = $userId;
        $post->ip = $ip;
        $post->reply_id = $replyId;
        $post->is_first = $isFirst;

        // Set content last, as the parsing may rely on other post attributes.
        $post->content = $content;

        $post->raise(new Created($post));

        return $post;
    }

    /**
     * Define the relationship with the post's thread.
     *
     * @return BelongsTo
     */
    public function thread()
    {
        return $this->belongsTo(Thread::class);
    }
}
