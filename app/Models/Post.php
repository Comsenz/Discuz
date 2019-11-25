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
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $user_id
 * @property int $thread_id
 * @property int $reply_id
 * @property string $content
 * @property string $ip
 * @property int $reply_count
 * @property int $like_count
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 * @property int $deleted_user_id
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
     * {@inheritdoc}
     */
    protected $casts = [
        'is_first' => 'boolean',
        'is_approved' => 'boolean',
    ];

    /**
     * {@inheritdoc}
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * The user for which the state relationship should be loaded.
     *
     * @var User
     */
    protected static $stateUser;

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
     * Refresh the thread's post count.
     *
     * @return $this
     */
    public function refreshLikeCount()
    {
        $this->like_count = $this->likedUsers()->count();

        return $this;
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

    /**
     * Define the relationship with the post's author.
     *
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Define the relationship with the user who hid the post.
     *
     * @return BelongsTo
     */
    public function deletedUser()
    {
        return $this->belongsTo(User::class, 'deleted_user_id');
    }

    /**
     * Define the relationship with the post's liked users.
     *
     * @return BelongsToMany
     */
    public function likedUsers()
    {
        return $this->belongsToMany(User::class)->withPivot('created_at');
    }

    /**
     * Define the relationship with the post's attachments.
     *
     * @return HasMany
     */
    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }

    /**
     * Define the relationship with the discussion's state for a particular user.
     *
     * @param User|null $user
     * @return HasOne
     */
    public function likeState(User $user = null)
    {
        $user = $user ?: static::$stateUser;

        return $this->hasOne(PostUser::class)->where('user_id', $user ? $user->id : null);
    }

    /**
     * Set the user for which the state relationship should be loaded.
     *
     * @param User $user
     */
    public static function setStateUser(User $user)
    {
        static::$stateUser = $user;
    }
}
