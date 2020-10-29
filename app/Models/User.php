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

use App\Traits\Notifiable;
use Carbon\Carbon;
use Discuz\Auth\Guest;
use Discuz\Contracts\Setting\SettingsRepository;
use Discuz\Database\ScopeVisibilityTrait;
use Discuz\Foundation\EventGeneratorTrait;
use Discuz\Http\UrlGenerator;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Contracts\Filesystem\Factory as Filesystem;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property int $sex
 * @property string $username
 * @property string $mobile
 * @property string $password
 * @property string $pay_password
 * @property string $avatar
 * @property int $status
 * @property string $union_id
 * @property string $last_login_ip
 * @property int $last_login_port
 * @property string $register_ip
 * @property int $register_port
 * @property string $register_reason
 * @property string $signature
 * @property string $username_bout
 * @property int $thread_count
 * @property int $follow_count
 * @property int $fans_count
 * @property int $liked_count
 * @property int $question_count
 * @property Carbon $login_at
 * @property Carbon $avatar_at
 * @property Carbon $joined_at
 * @property Carbon $expired_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $identity
 * @property string $realname
 * @property bool $denyStatus
 * @property Collection $groups
 * @property userFollow $follow
 * @property UserWallet $userWallet
 * @property UserWechat $wechat
 * @property UserDistribution $userDistribution
 * @property User $deny
 * @method truncate()
 * @method hasAvatar()
 */
class User extends Model
{
    use EventGeneratorTrait;
    use ScopeVisibilityTrait;
    use Notifiable;

    /**
     * @var bool
     */
    public $timestamps = true;

    /**
     * {@inheritdoc}
     */
    protected $casts = [
    ];

    /**
     * {@inheritdoc}
     */
    protected $dates = [
        'avatar_at',
        'login_at',
        'joined_at',
        'expired_at',
        'created_at',
        'updated_at',
    ];

    /**
     * {@inheritdoc}
     */
    protected $fillable = [
        'id',
        'username',
        'password',
        'mobile'
    ];

    /**
     * 枚举 - status
     *
     * 0 正常 1 禁用 2 审核中 3 审核拒绝 4 审核忽略
     * @var array
     */
    protected static $status = [
        'normal' => 0,
        'ban' => 1,
        'mod' => 2,
        'refuse' => 3,
        'ignore' => 4,
    ];

    /**
     * An array of permissions that this user has.
     *
     * @var string[]|null
     */
    protected $permissions = null;

    /**
     * The hasher with which to hash passwords.
     *
     * @var Hasher
     */
    protected static $hasher;

    /**
     * The access gate.
     *
     * @var Gate
     */
    protected static $gate;

    /**
     * Register a new user.
     *
     * @param array data
     * @return static
     */
    public static function register(array $data)
    {
        $user = new static;
        $user->attributes = $data;
        $user->joined_at = Carbon::now();
        $user->login_at = Carbon::now();
        $user->setPasswordAttribute($user->password);

        // 将名字中的空白字符替换为空
        $user->username = preg_replace('/\s/ui', '', $user->username);

        return $user;
    }

    /**
     * 根据 值/类型 获取对应值
     *
     * @param mixed $mixed
     * @return mixed
     */
    public static function enumStatus($mixed)
    {
        $arr = static::$status;

        if (is_numeric($mixed)) {
            return array_search($mixed, $arr);
        }

        return $arr[$mixed];
    }

    /**
     * @return Gate
     */
    public static function getGate()
    {
        return static::$gate;
    }

    /**
     * @param Gate $gate
     */
    public static function setGate($gate)
    {
        static::$gate = $gate;
    }

    /**
     * Change the user's password.
     *
     * @param string $password
     * @return $this
     */
    public function changePassword($password)
    {
        $this->password = $password;

        // $this->raise(new PasswordChanged($this));

        return $this;
    }

    public function changePayPassword($password)
    {
        $this->pay_password = $password;

        // $this->raise(new PayPasswordChanged($this));

        return $this;
    }

    /**
     * @param string $path
     * @param bool $isRemote
     * @return $this
     */
    public function changeAvatar($path, $isRemote = false)
    {
        $this->avatar = ($isRemote ? 'cos://' : '') . $path;
        $this->avatar_at = $path ? Carbon::now() : null;

        return $this;
    }

    public function changeMobile($mobile)
    {
        $this->mobile = $mobile;

        return $this;
    }

    public function changeStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    public function changeRealname($realname)
    {
        $this->realname = $realname;

        return $this;
    }

    public function changeIdentity($identity)
    {
        $this->identity = $identity;

        return $this;
    }

    public function changeUpdateAt()
    {
        $this->updated_at = Carbon::now();

        return $this;
    }

    public function changeUsername($username, $isAdmin = false)
    {
        $this->username = $username;

        if (!$isAdmin) {
            // 修改次数+1
            $this->username_bout += 1;
        }

        return $this;
    }

    public function changeSignature($signature)
    {
        $this->signature = $signature;

        return $this;
    }

    /**
     * Check if a given password matches the user's password.
     *
     * @param string $password
     * @return bool
     */
    public function checkPassword($password)
    {
        return static::$hasher->check($password, $this->password);
    }

    /**
     * Check if a given password matches the user's wallet pay password.
     *
     * @param string $password
     * @return bool
     */
    public function checkWalletPayPassword($password)
    {
        return static::$hasher->check($password, $this->pay_password);
    }

    /*
    |--------------------------------------------------------------------------
    | 修改器
    |--------------------------------------------------------------------------
    */

    /**
     * Set the password attribute, storing it as a hash.
     *
     * @param string $value
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = $value ? static::$hasher->make($value) : '';
    }

    public function setPayPasswordAttribute($value)
    {
        $this->attributes['pay_password'] = $value ? static::$hasher->make($value) : '';
    }

    /**
     * @param $value
     * @return string
     * @throws \Exception
     */
    public function getAvatarAttribute($value)
    {
        if (empty($value)) {
            return $value;
        }

        if (strpos($value, '://') === false) {
            return app(UrlGenerator::class)->to('/storage/avatars/' . $value)
                . '?' . Carbon::parse($this->avatar_at)->timestamp;
        }

        /** @var SettingsRepository $settings */
        $settings = app(SettingsRepository::class);

        $path = 'public/avatar/' . Str::after($value, '://');

        if ($settings->get('qcloud_cos_sign_url', 'qcloud', true)) {
            return app(Filesystem::class)->disk('avatar_cos')->temporaryUrl($path, Carbon::now()->addDay());
        } else {
            return app(Filesystem::class)->disk('avatar_cos')->url($path)
                . '?' . Carbon::parse($this->avatar_at)->timestamp;
        }
    }

    public function getMobileAttribute($value)
    {
        return $value ? substr_replace($value, '****', 3, 4) : '';
    }

    public function getRealnameAttribute($value)
    {
        return $value ?  : '';
    }

    public function getIdentityAttribute($value)
    {
        return $value ?  substr_replace($value, '****************', 1, 16) : '';
    }

    /*
    |--------------------------------------------------------------------------
    | 常用方法
    |--------------------------------------------------------------------------
    */

    /**
     * Refresh the thread's comments count.
     *
     * @return $this
     */
    public function refreshThreadCount()
    {
        $this->thread_count = $this->threads()
            ->where('is_approved', Thread::APPROVED)
            ->where('type', '<>', Thread::TYPE_OF_QUESTION)
            ->whereNull('deleted_at')
            ->count();

        return $this;
    }

    /**
     * 刷新用户问答数，包括提问与回答
     *
     * @return $this
     */
    public function refreshQuestionCount()
    {
        $this->question_count = Thread::query()
            ->join('questions', 'threads.id', '=', 'questions.thread_id')
            ->where('threads.type', Thread::TYPE_OF_QUESTION)
            ->where('threads.is_approved', Thread::APPROVED)
            ->whereNull('threads.deleted_at')
            ->where(function (Builder $query) {
                $query->where('threads.user_id', $this->id)->orWhere('questions.be_user_id', $this->id);
            })
            ->count();

        return $this;
    }

    public function getUnreadNotificationCount()
    {
        static $cached = null;
        if (is_null($cached)) {
            $cached = $this->unreadNotifications()->count();
        }
        return $cached;
    }

    public function getUnreadTypesNotificationCount()
    {
        static $cachedAll = null;
        if (is_null($cachedAll)) {
            $cachedAll = $this->unreadNotifications()->selectRaw('type,count(*) as count')
                ->groupBy('type')->pluck('type', 'count')->map(function ($val) {
                    return class_basename($val);
                })->flip();
        }
        return $cachedAll;
    }

    /**
     * Check whether or not the user is an administrator.
     *
     * @return bool
     */
    public function isAdmin()
    {
        if ($this instanceof Guest) {
            return false;
        }

        return $this->groups->contains(Group::ADMINISTRATOR_ID);
    }

    /**
     * Check whether or not the user is a guest.
     *
     * @return bool
     */
    public function isGuest()
    {
        return false;
    }

    /**
     * 刷新用户关注数
     * @return $this
     */
    public function refreshUserFollow()
    {
        $this->follow_count = $this->userFollow()->count();
        return $this;
    }

    /**
     * 刷新用户粉丝数
     * @return $this
     */
    public function refreshUserFans()
    {
        $this->fans_count = $this->userFans()->count();
        return $this;
    }

    /**
     * 刷新用户点赞主题数
     * @return $this
     */
    public function refreshUserLiked()
    {
        $this->liked_count = $this->postUser()
            ->join('posts', 'post_user.post_id', '=', 'posts.id')
            ->where('posts.is_first', true)
            ->where('posts.is_approved', Post::APPROVED)
            ->whereNull('posts.deleted_at')
            ->count();

        return $this;
    }

    /**
     * 注册用户创建一个随机用户名
     *
     * @return string
     */
    public static function getNewUsername()
    {
        $username = trans('validation.attributes.username_prefix') . Str::random(6);
        $user = User::query()->where('username', $username)->first();
        if ($user) {
            return self::getNewUsername();
        }
        return $username;
    }

    /**
     * 判断是否有上级 & 上级是否可以推广下线分成
     *
     * @param int $type 1推广下线 2/3收入提成
     * @return bool
     */
    public function isAllowScale($type)
    {
        switch ($type) {
            case Order::ORDER_TYPE_REGISTER:
                // 注册分成查询付款人的上级
                if (!empty($userDistribution = $this->userDistribution)) {
                    return (bool) $userDistribution->is_subordinate;
                }
                break;
            case Order::ORDER_TYPE_REWARD:
            case Order::ORDER_TYPE_THREAD:
                // 打赏/付费分成查询收款人的上级
                if (!empty($userDistribution = $this->userDistribution)) {
                    return (bool) $userDistribution->is_commission;
                }
                break;
        }

        return false;
    }

    /*
    |--------------------------------------------------------------------------
    | 关联模型
    |--------------------------------------------------------------------------
    */

    public function logs()
    {
        return $this->morphMany(UserActionLogs::class, 'log_able');
    }

    public function latelyLog()
    {
        return $this->hasOne(UserActionLogs::class, 'log_able_id')->orderBy('id', 'desc');
    }

    public function wechat()
    {
        return $this->hasOne(UserWechat::class);
    }

    /**
     * Define the relationship with the user's posts.
     *
     * @return HasMany
     */
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Define the relationship with the user's threads.
     *
     * @return HasMany
     */
    public function threads()
    {
        return $this->hasMany(Thread::class);
    }

    /**
     * Define the relationship with the user's orders.
     *
     * @return HasMany
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Define the relationship with the user's groups.
     *
     * @return BelongsToMany
     */
    public function groups()
    {
        return $this->belongsToMany(Group::class)
            ->withPivot('expiration_time');
    }

    /**
     * Define the relationship with the user's favorite threads.
     *
     * @return BelongsToMany
     */
    public function favoriteThreads()
    {
        return $this->belongsToMany(Thread::class)
            ->as('favoriteState')
            ->withPivot('created_at')
            ->whereNull('threads.deleted_at')
            ->whereNotNull('threads.user_id')
            ->where('threads.is_approved', Thread::APPROVED);
    }

    /**
     * Define the relationship with the user's liked posts.
     *
     * @return BelongsToMany
     */
    public function likedPosts()
    {
        return $this->belongsToMany(Post::class);
    }

    /**
     * Define the relationship with the user's wallet.
     *
     * @return hasOne
     */
    public function userWallet()
    {
        return $this->hasOne(UserWallet::class);
    }

    /**
     * Define the relationship with the user's follow.
     *
     * @return HasMany
     */
    public function userFollow()
    {
        return $this->hasMany(UserFollow::class, 'from_user_id');
    }

    /**
     * Define the relationship with the user's fans.
     *
     * @return HasMany
     */
    public function userFans()
    {
        return $this->hasMany(UserFollow::class, 'to_user_id');
    }

    public function postUser()
    {
        return $this->hasMany(PostUser::class);
    }

    public function userDistribution()
    {
        return $this->hasOne(UserDistribution::class);
    }

    /*
    |--------------------------------------------------------------------------
    | 权限验证
    |--------------------------------------------------------------------------
    */

    /**
     * Define the relationship with the permissions of all of the groups that
     * the user is in.
     *
     * @return Builder
     */
    public function permissions()
    {
        $groupIds = (Arr::get($this->getRelations(), 'groups') ?? $this->groups)->pluck('id')->all();

        return Permission::query()->whereIn('group_id', $groupIds);
    }

    /**
     * Get a list of permissions that the user has.
     *
     * @return string[]
     */
    public function getPermissions()
    {
        return $this->permissions()->pluck('permission')->all();
    }

    /**
     * 检查用户是否具有一定的权限基于他们的用户组。
     * 传入字符串时，返回是否具有此权限。
     * 传入数组时，如果第二个参数为 true (default) 返回是否同时具有这些权限，
     * 如果第二个参数为 false 则返回是否具有这些权限其中之一。
     *
     * @param string|array $permission
     * @param bool $condition
     * @return bool
     */
    public function hasPermission($permission, bool $condition = true)
    {
        if ($this->isAdmin()) {
            return true;
        }

        if (is_null($this->permissions)) {
            $this->permissions = $this->getPermissions();
        }

        if (is_array($permission)) {
            foreach ($permission as $item) {
                if ($condition) {
                    if (! in_array($item, $this->permissions)) {
                        return false;
                    }
                } else {
                    if (in_array($item, $this->permissions)) {
                        return true;
                    }
                }
            }

            return $condition;
        } else {
            return in_array($permission, $this->permissions);
        }
    }

    /**
     * @param string $ability
     * @param array|mixed $arguments
     * @return bool
     */
    public function can($ability, $arguments = [])
    {
        return static::$gate->forUser($this)->allows($ability, $arguments);
    }

    /**
     * @param string $ability
     * @param array|mixed $arguments
     * @return bool
     */
    public function cannot($ability, $arguments = [])
    {
        return ! $this->can($ability, $arguments);
    }

    /**
     * Set the hasher with which to hash passwords.
     *
     * @param Hasher $hasher
     */
    public static function setHasher(Hasher $hasher)
    {
        static::$hasher = $hasher;
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeHasAvatar($query)
    {
        return $query->whereNotNull('avatar');
    }

    /**
     * @return mixed
     */
    public function deny()
    {
        return $this->belongsToMany(User::class, 'deny_users', 'user_id', 'deny_user_id', null, null, 'deny');
    }

    /**
     * @return mixed
     */
    public function denyFrom()
    {
        return $this->belongsToMany(User::class, 'deny_users', 'deny_user_id', 'user_id', null, null, 'denyFrom');
    }
}
