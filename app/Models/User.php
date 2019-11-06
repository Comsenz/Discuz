<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use App\Events\Users\Created;
use Discuz\Foundation\EventGeneratorTrait;
use Discuz\Database\ScopeVisibilityTrait;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property string $username
 * @property string $password
 * @property int $admin_id
 * @property string $mobile
 * @property string $login_ip
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @package App\Models
 */
class User extends Model
{
    use EventGeneratorTrait;
    use ScopeVisibilityTrait;

    /**
     * {@inheritdoc}
     */
    protected $fillable = [
        'id',
        'username',
        'password',
        'adminid',
        'mobile',
        'created_at'
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
     * TODO: rename to register
     * Register a new user.
     *
     * @param string $username
     * @param string $password
     * @param string $mobile
     * @param int $admin_id
     * @param string $ipAddress
     * @return static
     */
    public static function creation(
        $username,
        $password,
        $mobile,
        $admin_id,
        $ipAddress
    ) {
        $user = new static;

        $user->username = $username;
        $user->password = $password;
        $user->mobile = $mobile;
        $user->adminid = $admin_id;
        $user->login_ip = $ipAddress;

        $user->raise(new Created($user));

        return $user;
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
     * TODO: 修改密码（预留）
     * Change the user's password.
     *
     * @param string $password
     * @return $this
     */
    public function changePassword($password)
    {
        $this->password = $password;

        $this->raise(new PasswordChanged($this));

        return $this;
    }

    /**
     * TODO: 验证密码（预留）
     * Check if a given password matches the user's password.
     *
     * @param string $password
     * @return bool
     */
    public function checkPassword($password)
    {
        $valid = static::$dispatcher->until(new CheckingPassword($this, $password));

        if ($valid !== null) {
            return $valid;
        }

        return static::$hasher->check($password, $this->password);
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

    protected function setUserLoginPasswordAttr($value)
    {
        return md5($value);
    }

    protected function setUserPasswordAttr($value)
    {
        // return $this->hashManager->make($value);
        return password_hash($value, PASSWORD_BCRYPT);
    }

    protected function unsetUserPasswordAttr($value,$userpwd)
    {
        // return $this->hashManager->check($value,$userpwd);
        return password_verify($value,$userpwd);
    }

    /*
    |--------------------------------------------------------------------------
    | 常用方法
    |--------------------------------------------------------------------------
    */

    /**
     * TODO: 用户未读消息数
     * Get the number of unread notifications for the user.
     *
     * @return int
     */
    public function getUnreadNotificationCount()
    {
        return $this->getUnreadNotifications()->count();
    }

    /**
     * TODO: 用户未读消息
     * Get all notifications that have not been read yet.
     *
     * @return Collection
     */
    protected function getUnreadNotifications()
    {
        static $cached = null;

        if (is_null($cached)) {
            $cached = $this->notifications()
                ->whereIn('type', $this->getAlertableNotificationTypes())
                ->whereNull('read_at')
                ->where('is_deleted', false)
                ->whereSubjectVisibleTo($this)
                ->get();
        }

        return $cached;
    }

    /**
     * Check whether or not the user is an administrator.
     *
     * @return bool
     */
    public function isAdmin()
    {
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

    /*
    |--------------------------------------------------------------------------
    | 关联模型
    |--------------------------------------------------------------------------
    */

    /**
     * Define the relationship with the user's wechats.
     *
     * @return HasOne
     */
    public function userWechats()
    {
        return $this->hasOne("App\Models\UserWechat", "id", "id");
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
     * Define the relationship with the user's groups.
     *
     * @return BelongsToMany
     */
    public function groups()
    {
        return $this->belongsToMany(Group::class);
    }

    /**
     * TODO: 消息提醒
     * Define the relationship with the user's notifications.
     *
     * @return HasMany
     */
    public function notifications()
    {
        return $this->hasMany(''); // Notification::class
    }

    /**
     * Define the relationship with the user's favorite threads.
     *
     * @return BelongsToMany
     */
    public function favoriteThreads()
    {
        return $this->belongsToMany(Thread::class);
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
    public function permissions() {
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
     * TODO: hash （预留，可能用不到）
     * Set the hasher with which to hash passwords.
     *
     * @param Hasher $hasher
     */
    public static function setHasher(Hasher $hasher)
    {
        static::$hasher = $hasher;
    }
}
