<?php

namespace App\Models;

use Discuz\Auth\Guest;
use Illuminate\Contracts\Auth\Access\Gate;
use Illuminate\Database\Eloquent\Model;
use App\Events\Users\Created;
use Discuz\Foundation\EventGeneratorTrait;
use Discuz\Database\ScopeVisibilityTrait;

/**
 * @property int $id
 * @package App\Models
 */
class User extends Model {
    use EventGeneratorTrait;
    use ScopeVisibilityTrait;

    protected $table = 'users';

    public $timestamps = true;

    protected $dateFormat = 'U';

    const CREATED_AT = 'createtime';

    const UPDATED_AT = 'updatetime';

    /**
     * The access gate.
     *
     * @var Gate
     */
    protected static $gate;

    protected $fillable = ['id', 'username','password','adminid','mobile','createtime'];

    protected $permissions = null;

    protected function setUserLoginPasswordAttr($value)
    {
        return md5($value);
    }

    public function cannot($ability, $arguments = [])
    {
        return ! $this->can($ability, $arguments);
    }

    public function hasPermission($permission)
    {

        if($this->isAdmin()) {
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
     *
     * @return static
     */
    public static function creation(
        $username,
        $password,
        $mobile,
        $adminid,
        $ipAddress
    ) {
        // 实例一个圈子模型
        $user = new static;

        // 设置模型属性值
        $user->username = $username;
        $user->password = $password;
        $user->mobile = $mobile;
        $user->adminid = $adminid;
        $user->login_ip = $ipAddress;

        // 暂存需要执行的事件
        $user->raise(new Created($user));
        // 返回模型

        return $user;
    }

    public function getPermissions() {
        return $this->permissions()->pluck('permission')->all();
    }

    public function permissions() {
        $groupIds = $this->groups->pluck('id')->all();

        return Permission::whereIn('group_id', $groupIds);
    }

    public function groups() {
        return $this->belongsToMany(Group::class);
    }

    public function isAdmin() {
        if($this instanceof Guest) {
            return false;
        }
        return $this->groups->contains(Group::ADMINISTRATOR_ID);
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
    public function userWechats()
    {
        return $this->hasOne("App\Models\UserWechat", "id", "id");
    }

    public function userProfiles()
    {
        return $this->hasOne("App\Models\UserProfile", "id", "user_id");
    }
}
