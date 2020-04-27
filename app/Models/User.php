<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Models;

use App\Traits\Notifiable;
use Discuz\Auth\Guest;
use Discuz\Http\UrlGenerator;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Discuz\Foundation\EventGeneratorTrait;
use Discuz\Database\ScopeVisibilityTrait;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

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
 * @property string $register_ip
 * @property string $register_reason
 * @property string $signature
 * @property string $username_bout
 * @property int $thread_count
 * @property int $follow_count
 * @property int $fans_count
 * @property int $liked_count
 * @property Carbon $login_at
 * @property Carbon $avatar_at
 * @property Carbon $joined_at
 * @property Carbon $expired_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $identity
 * @property string $realname
 * @property Group $groups
 * @property userFollow $follow
 * @property UserWallet $userWallet
 * @property UserWechat $wechat
 * @package App\Models
 * @method truncate()
 * @method hasAvatar()
 * @method static find($id)
 * @method static whereIn($field, $ids)
 * @method static findOrfail($id)
 * @method static where($column, $array)
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

    public function changeAvatar($path)
    {
        $this->avatar = $path;

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

    public function getAvatarAttribute($value)
    {
        if ($value && strpos($value, '://') === false) {
            return app(UrlGenerator::class)->to('/storage/avatars/'.$value);
        }

        return $value;
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
            ->whereNull('deleted_at')
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
            ->count();
        return $this;
    }

    /*
    |--------------------------------------------------------------------------
    | 关联模型
    |--------------------------------------------------------------------------
    */

    public function logs()
    {
        return $this->morphMany(OperationLog::class, 'log_able');
    }

    public function latelyLog()
    {
        return $this->hasOne(OperationLog::class, 'log_able_id')->orderBy('id', 'desc');
    }

    public function wechat()
    {
        return $this->hasOne(UserWechat::class);
    }

    /**
     * Define the relationship with the user's profiles.
     *
     * @return HasOne
     */
    public function userProfiles()
    {
        return $this->hasOne(UserProfile::class);
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
        return $this->belongsToMany(Group::class);
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
            ->withPivot('created_at');
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
        $groupIds = $this->groups->pluck('id')->all();

        return Permission::whereIn('group_id', $groupIds);
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
     * Check whether the user has a certain permission based on their groups.
     *
     * @param string $permission
     * @return bool
     */
    public function hasPermission($permission)
    {
        if ($this->isAdmin()) {
            return true;
        }

        if (is_null($this->permissions)) {
            $this->permissions = $this->getPermissions();
        }

        return in_array($permission, $this->permissions);
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
}
