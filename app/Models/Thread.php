<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: Threads.php xxx 2019-10-09 20:14:00 LiuDongdong $
 */

namespace App\Models;

use Carbon\Carbon;
use Discuz\Database\ScopeVisibilityTrait;
use Discuz\Foundation\EventGeneratorTrait;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $user_id
 * @property int $last_posted_user_id
 * @property string $title
 * @property float $price
 * @property int $post_count
 * @property int $view_count
 * @property int $like_count
 * @property int $favorite_count
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 * @property int $deleted_user_id
 * @property bool $is_approved
 * @property bool $is_sticky
 * @property bool $is_essence
 * @package App\Models
 */
class Thread extends Model
{
    use EventGeneratorTrait;
    use ScopeVisibilityTrait;
    use SoftDeletes;

    /**
     * {@inheritdoc}
     */
    protected $casts = [
        'is_approved' => 'boolean',
        'is_sticky' => 'boolean',
        'is_essence' => 'boolean',
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
     * Get the last three posts of the thread.
     *
     * @return Collection
     */
    public function lastThreePosts()
    {
        return $this->posts()
            ->where('is_first', false)
            ->limit(3)
            ->get();
    }

    /**
     * Set the thread's last post details.
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

    /**
     * Refresh a thread's last post details.
     *
     * @return $this
     */
    public function refreshLastPost()
    {
        /** @var Post $lastPost */
        if ($lastPost = $this->replies()->latest()->first()) {
            $this->setLastPost($lastPost);
        }

        return $this;
    }

    /**
     * Refresh the thread's post count.
     *
     * @return $this
     */
    public function refreshPostCount()
    {
        $this->post_count = $this->replies()->count();

        return $this;
    }

    /**
     * Define the relationship with the thread's publicly-visible posts.
     *
     * @return HasMany
     */
    public function replies()
    {
        return $this->posts()->where('is_approved', true);
    }

    /**
     * Define the relationship with the thread's first post.
     *
     * @return HasOne
     */
    public function firstPost()
    {
        return $this->hasOne(Post::class)->where('is_first', true);
    }

    /**
     * Define the relationship with the thread's author.
     *
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Define the relationship with the thread's posts.
     *
     * @return HasMany
     */
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Define the relationship with the thread's favorite users.
     *
     * @return BelongsToMany
     */
    public function favoriteUsers()
    {
        return $this->belongsToMany(User::class)->withPivot('created_at');
    }

    /**
     * Define the relationship with the discussion's state for a particular user.
     *
     * @param User|null $user
     * @return HasOne
     */
    public function favoriteState(User $user = null)
    {
        $user = $user ?: static::$stateUser;

        return $this->hasOne(ThreadUser::class)->where('user_id', $user ? $user->id : null);
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
