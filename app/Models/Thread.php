<?php

/**
 * Copyright (C) 2020 Tencent Cloud.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace App\Models;

use App\Events\Thread\Hidden;
use App\Events\Thread\Restored;
use Carbon\Carbon;
use DateTime;
use Discuz\Auth\Anonymous;
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
use Illuminate\Support\Stringable;

/**
 * @property int $id
 * @property int $user_id
 * @property int $last_posted_user_id
 * @property int $category_id
 * @property int $type
 * @property string $title
 * @property float $price
 * @property float $attachment_price
 * @property int $free_words
 * @property int $post_count
 * @property int $view_count
 * @property int $paid_count
 * @property int $rewarded_count
 * @property float $longitude
 * @property float $latitude
 * @property string $address
 * @property string $location
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 * @property int $deleted_user_id
 * @property int $is_approved
 * @property bool|null $is_paid
 * @property bool|null $is_paid_attachment
 * @property bool $is_sticky
 * @property bool $is_essence
 * @property bool $is_site
 * @property bool $is_anonymous
 * @property bool $is_display
 * @property Post $firstPost
 * @property Collection $topic
 * @property Collection $orders
 * @property User $user
 * @property Category $category
 * @property threadVideo $threadVideo
 * @property Question $question
 * @property Order $onlookerState
 * @method increment($column, $amount = 1, array $extra = [])
 * @method decrement($column, $amount = 1, array $extra = [])
 */
class Thread extends Model
{
    use EventGeneratorTrait;
    use ScopeVisibilityTrait;

    const TYPE_OF_TEXT = 0;

    const TYPE_OF_LONG = 1;

    const TYPE_OF_VIDEO = 2;

    const TYPE_OF_IMAGE = 3;

    const TYPE_OF_AUDIO = 4;

    const TYPE_OF_QUESTION = 5;

    const TYPE_OF_GOODS = 6;

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
        'is_anonymous' => 'boolean',
        'is_display' => 'boolean',
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
     * 主题下已付费用户列表，以用户 id 为键，以主题 id 的数组为值。
     *
     * @var Collection
     */
    protected static $userHasPaidThreads;

    protected static $userHasPaidThreadAttachments;

    /**
     * datetime 时间转换
     *
     * @param $timeAt
     * @return string
     */
    public function formatDate($timeAt)
    {
        return $this->{$timeAt}->format(DateTime::RFC3339);
    }

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
     * @param bool $parse
     * @return Stringable|string
     */
    public function getContentByType($substr, $parse = false)
    {
        $special = app(SpecialCharServer::class);

        if ($this->type == 1) {
            $content = $substr ? Str::of($this->title)->substr(0, $substr) : $this->title;
            $content = $special->purify($content);
        } else {
            // 不是长文没有标题则使用首贴内容
            $this->firstPost->content = $substr ? Str::of($this->firstPost->content)->substr(0, $substr) : $this->firstPost->content;
            if ($parse) {
                // 原文
                $content = $this->firstPost->content;
            } else {
                $content = $this->firstPost->formatContent();
            }
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
        $this->updated_at = $post->created_at->gt($this->updated_at) ? $post->created_at : $this->updated_at;

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
        $this->post_count = $this->posts()
            ->where('is_first', false)
            ->where('is_comment', false)
            ->where('is_approved', Post::APPROVED)
            ->whereNull('deleted_at')
            ->whereNotNull('user_id')
            ->count() + 1;  // include first post

        return $this;
    }

    /**
     * 刷新付费数量
     *
     * @return $this
     */
    public function refreshPaidCount()
    {
        $this->paid_count = $this->orders()
            ->whereIn('type', [Order::ORDER_TYPE_THREAD, Order::ORDER_TYPE_ATTACHMENT])
            ->where('status', Order::ORDER_STATUS_PAID)
            ->count();

        return $this;
    }

    /**
     * 刷新打赏数量
     *
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
        return $this->belongsTo(User::class, 'deleted_user_id')->withDefault([
            'username' => trans('user.user_has_deleted'),
        ]);
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
     * 查询主题下某一种交易类型
     * （交易类型：1注册、2打赏、3付费主题、4付费用户组、5问答提问支付、6问答付费围观、7付费附件）
     *
     * @param int $type
     * @param bool $more
     * @return Collection|Order|Model
     */
    public function ordersByType($type, $more = true)
    {
        $query = $this->orders()->where('type', $type);

        return $more ? $query->get() : $query->first();
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

    public function threadVideo()
    {
        return $this->hasOne(ThreadVideo::class)->where('type', ThreadVideo::TYPE_OF_VIDEO);
    }

    public function threadAudio()
    {
        return $this->hasOne(ThreadVideo::class)->where('type', ThreadVideo::TYPE_OF_AUDIO);
    }

    public function topic()
    {
        return $this->belongsToMany(Topic::class)->withPivot('created_at');
    }

    public function threadTopic()
    {
        return $this->hasMany(ThreadTopic::class);
    }

    public function question()
    {
        return $this->hasOne(Question::class);
    }

    /**
     * 获取匿名用户名
     *
     * @return string
     */
    public function isAnonymousName()
    {
        return $this->is_anonymous ? (new Anonymous)->getUsername() : $this->user->username;
    }

    /**
     * Set the user for which the state relationship should be loaded.
     *
     * @param User $user
     * @param Collection|null $threads
     */
    public static function setStateUser(User $user, Collection $threads = null)
    {
        static::$stateUser = $user;

        // 当前用户对于传入主题列表是否付费
        if ($threads) {
            foreach ([Order::ORDER_TYPE_THREAD, Order::ORDER_TYPE_ATTACHMENT] as $type) {
                $data = [];
                $orders = Order::query()
                    ->whereIn('thread_id', $threads->pluck('id'))
                    ->where('user_id', $user->id)
                    ->where('status', Order::ORDER_STATUS_PAID)
                    ->where('type', $type)
                    ->pluck('thread_id');

                $data[$user->id] = $threads->keyBy('id')
                    ->map(function ($thread) use ($orders) {
                        return $orders->contains($thread->id);
                    });
                if ($type == Order::ORDER_TYPE_THREAD) {
                    // 主题付费数据
                    static::$userHasPaidThreads = $data;
                } elseif ($type == Order::ORDER_TYPE_ATTACHMENT) {
                    // 主题附件付费数据
                    static::$userHasPaidThreadAttachments = $data;
                }
            }
        }
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

    /**
     * Define the relationship with the question's onlooker state for a particular user.
     *
     * @param User|null $user
     * @return HasOne
     */
    public function onlookerState(User $user = null)
    {
        $user = $user ?: static::$stateUser;

        return $this->hasOne(Order::class)
            ->where('orders.user_id', $user ? $user->id : null)
            ->where('orders.status', Order::ORDER_STATUS_PAID)
            ->where('orders.type', Order::ORDER_TYPE_ONLOOKER);
    }

    /**
     * 主题对于某用户是否付费主题
     *
     * @return bool|null
     */
    public function getIsPaidAttribute()
    {
        $user = static::$stateUser;

        // 必须有用户
        if (! $user) {
            throw new \RuntimeException('You must set the user with setStateUser()');
        }

        // 非付费主题返回 null
        if ($this->price <= 0) {
            return null;
        }

        // 用户不存在返回 false
        if (! $user->exists) {
            return false;
        }

        // 作者本人 或 管理员 返回 true
        if ($this->user_id === $user->id || $user->isAdmin()) {
            return true;
        }

        // 是否已缓存付费状态（为避免 N + 1 问题）
        if (isset(static::$userHasPaidThreads[$user->id][$this->id])) {
            return static::$userHasPaidThreads[$user->id][$this->id];
        }

        $isPaid = Order::query()
            ->where('user_id', $user->id)
            ->where('thread_id', $this->id)
            ->where('status', Order::ORDER_STATUS_PAID)
            ->where('type', Order::ORDER_TYPE_THREAD)
            ->exists();
        static::$userHasPaidThreads[$user->id][$this->id] = $isPaid;

        return $isPaid;
    }

    /**
     * 获取附件付费状态
     *
     * @return bool|null
     */
    public function getIsPaidAttachmentAttribute()
    {
        $user = static::$stateUser;

        // 必须有用户
        if (! $user) {
            throw new \RuntimeException('You must set the user with setStateUser()');
        }

        // 非付费主题返回 null
        if ($this->attachment_price <= 0) {
            return null;
        }

        // 用户不存在返回 false
        if (! $user->exists) {
            return false;
        }

        // 作者本人 或 管理员 返回 true
        if ($this->user_id === $user->id || $user->isAdmin()) {
            return true;
        }

        // 是否已缓存付费状态（为避免 N + 1 问题）
        if (isset(static::$userHasPaidThreadAttachments[$user->id][$this->id])) {
            return static::$userHasPaidThreadAttachments[$user->id][$this->id];
        }

        $isPaidAttachment = Order::query()
            ->where('user_id', $user->id)
            ->where('thread_id', $this->id)
            ->where('status', Order::ORDER_STATUS_PAID)
            ->where('type', Order::ORDER_TYPE_ATTACHMENT)
            ->exists();
        static::$userHasPaidThreadAttachments[$user->id][$this->id] = $isPaidAttachment;

        return $isPaidAttachment;
    }
}
