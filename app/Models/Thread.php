<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Models;

use App\Events\Thread\Hidden;
use App\Events\Thread\Restored;
use Carbon\Carbon;
use Discuz\Database\ScopeVisibilityTrait;
use Discuz\Foundation\EventGeneratorTrait;
use Discuz\SpecialChar\SpecialCharServer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property int $user_id
 * @property int $last_posted_user_id
 * @property int $category_id
 * @property string $title
 * @property float $price
 * @property int $free_words
 * @property int $post_count
 * @property int $view_count
 * @property int $paid_count
 * @property int $rewarded_count
 * @property int $favorite_count
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 * @property int $deleted_user_id
 * @property bool $is_approved
 * @property bool $is_sticky
 * @property bool $is_essence
 * @property int $type
 * @property Post $firstPost
 * @property Topic|Collection $topic
 * @property User $user
 * @property Category $category
 * @property threadVideo $threadVideo
 * @package App\Models
 * @method static find($id)
 * @property string getContentByType
 * @property int refreshPaidCount
 */
class Thread extends Model
{
    use EventGeneratorTrait;
    use ScopeVisibilityTrait;

    const UNAPPROVED = 0;

    const APPROVED = 1;

    const IGNORED = 2;

    /**
     * 通知内容展示长度(字)
     */
    const CONTENT_LENGTH = 80;

    /**
     * {@inheritdoc}
     */
    protected $casts = [
        'type' => 'integer',
        'price' => 'decimal:2',
        'free_words' => 'integer',
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
     * Hide the thread.
     *
     * @param User $actor
     * @param array $options
     * @return $this
     */
    public function hide(User $actor, $options = [])
    {
        if (! $this->deleted_at) {
            $this->deleted_at = Carbon::now();
            $this->deleted_user_id = $actor->id;

            $this->raise(new Hidden($this, $actor, $options));
        }

        return $this;
    }

    /**
     * Restore the thread.
     *
     * @param User $actor
     * @param array $options
     * @return $this
     */
    public function restore(User $actor, $options = [])
    {
        if ($this->deleted_at !== null) {
            $this->deleted_at = null;
            $this->deleted_user_id = null;

            $this->raise(new Restored($this, $actor, $options));
        }

        return $this;
    }

    /**
     * 根据类型获取 Thread content
     *
     * @param int $substr
     * @return \Illuminate\Support\Stringable|string
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function getContentByType($substr = 0)
    {
        $special = app()->make(SpecialCharServer::class);

        if ($this->type == 1) {
            $content = $substr ? Str::of($this->title)->substr(0, $substr) : $this->title;
            $content = $special->purify($content);
        } else {
            // 不是长文没有标题则使用首贴内容
            $this->firstPost->content = $substr ? Str::of($this->firstPost->content)->substr(0, $substr) : $this->firstPost->content;
            $content = $this->firstPost->formatContent();
        }

        return $content;
    }

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
        $this->post_count = $this->replies()
            ->where('is_approved', Post::APPROVED)
            ->whereNull('deleted_at')
            ->count();

        return $this;
    }

    /**
     * 刷新付费数量
     * @return $this
     */
    public function refreshPaidCount()
    {
        $this->paid_count = $this->orders()
            ->where('type', Order::ORDER_TYPE_THREAD)
            ->where('status', Order::ORDER_STATUS_PAID)
            ->count();

        return $this;
    }

    /**
     * 刷新打赏数量
     * @return $this
     */
    public function refreshRewardedCount()
    {
        $this->rewarded_count = $this->orders()
            ->where('type', Order::ORDER_TYPE_REWARD)
            ->where('status', Order::ORDER_STATUS_PAID)
            ->count();

        return $this;
    }

    /**
     * Define the relationship with the thread's publicly-visible posts.
     *
     * @return HasMany
     */
    public function replies()
    {
        return $this->posts()->where('is_approved', Thread::APPROVED);
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
     * Define the relationship with the thread's first post.
     *
     * @return hasMany
     */
    public function rewarded()
    {
        return $this->hasMany(Order::class, 'type_id')
            ->where('type', 2)
            ->where('status', 1);
    }

    /**
     * Define the relationship with the thread's category.
     *
     * @return BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
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
     * Define the relationship with the thread's author.
     *
     * @return BelongsTo
     */
    public function lastPostedUser()
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
     * Define the relationship with the thread's posts.
     *
     * @return HasMany
     */
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Define the relationship with the thread's orders.
     *
     * @return HasMany
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Define the relationship with the thread's operation Log.
     */
    public function logs()
    {
        return $this->morphMany(UserActionLogs::class, 'log_able');
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
     * Define the relationship with the thread's favorite state for a particular user.
     *
     * @param User|null $user
     * @return HasOne
     */
    public function favoriteState(User $user = null)
    {
        $user = $user ?: static::$stateUser;

        return $this->hasOne(ThreadUser::class)->where('user_id', $user ? $user->id : null);
    }

    public function threadVideo()
    {
        return $this->hasOne(ThreadVideo::class);
    }


    public function topic()
    {
        return $this->belongsToMany(Topic::class)->withPivot('created_at');
    }

    public function threadTopic()
    {
        return $this->hasMany(ThreadTopic::class);
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
