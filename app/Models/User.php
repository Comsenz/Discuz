<?php

namespace App\Models;

use Carbon\Carbon;
use Discuz\Auth\Guest;
use Discuz\Http\UrlGenerator;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Discuz\Foundation\EventGeneratorTrait;
use Discuz\Database\ScopeVisibilityTrait;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Notifications\Notifiable;
use App\Notifications\DiscuzChannelManager;


/**
 * @property int $id
 * @property string $username
 * @property string $mobile
 * @property string $password
 * @property string $union_id
 * @property string $last_login_ip
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @package App\Models
 *
 */
class mobileUser extends Model
{
    use EventGeneratorTrait;
    use ScopeVisibilityTrait;
    use Notifiable;


    const MOBILE_ACTIVE = 1;

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
        $user->setPasswordAttribute($user->password);
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
     * Change the user's password.
     *
     * @param string $password
     * @return $this
     */
    public function changePassword($password)
    {
        $this->password = $password;

//        $this->raise(new PasswordChanged($this));

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

    public function changeMobileActive($active)
    {
        $this->mobile_confirmed = $active;

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

    public function getAvatarAttribute($value)
    {
        if ($value && strpos($value, '://') === false) {
            return app(UrlGenerator::class)->to('/storage/avatars/'.$value);
        }

        return $value;
    }

    /*
    |--------------------------------------------------------------------------
    | 常用方法
    |--------------------------------------------------------------------------
    */
    /**
     * 重载通知
     */
    public function notify($instance){
        app(DiscuzChannelManager::class)->send($this, $instance);
    }
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

    /*
    |--------------------------------------------------------------------------
    | 关联模型
    |--------------------------------------------------------------------------
    */

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
     * Set the hasher with which to hash passwords.
     *
     * @param Hasher $hasher
     */
    public static function setHasher(Hasher $hasher)
    {
        static::$hasher = $hasher;
    }
}
