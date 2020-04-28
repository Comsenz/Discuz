<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Models;

use App\Events\Group\Deleted;
use Discuz\Database\ScopeVisibilityTrait;
use Discuz\Foundation\EventGeneratorTrait;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property string $type
 * @property string $color
 * @property string $icon
 * @property int $default
 * @property int $is_display
 * @property Collection $users
 * @property Collection $permissions
 * @method truncate()
 * @method create(array $array)
 * @method insert(array $array)
 * @method static find(mixed $GUEST_ID)
 * @method static where(string $string, int $id)
 */
class Group extends Model
{
    use EventGeneratorTrait;
    use ScopeVisibilityTrait;

    /**
     * The ID of the administrator group.
     */
    const ADMINISTRATOR_ID = 1;

    /**
     * The ID of the banned group.
     */
    const BAN_ID = 5;

    /**
     * The ID of the unpaid group.
     */
    const UNPAID = 6;

    /**
     * The ID of the guest group.
     */
    const GUEST_ID = 7;

    /**
     * The ID of the member group.
     */
    const MEMBER_ID = 10;

    /**
     * {@inheritdoc}
     */
    public $timestamps = false;

    /**
     * {@inheritdoc}
     */
    protected $casts = [
        'default' => 'boolean',
        'is_display' => 'boolean',
    ];

    /**
     * {@inheritdoc}
     */
    protected $fillable = ['id', 'name', 'type', 'color', 'icon', 'default'];

    /**
     * {@inheritdoc}
     */
    protected static function boot()
    {
        parent::boot();

        static::deleted(function (self $group) {
            $group->raise(new Deleted($group));
        });
    }

    /**
     * Define the relationship with the group's users.
     *
     * @return BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * Define the relationship with the group's permissions.
     *
     * @return HasMany
     */
    public function permission()
    {
        return $this->hasMany(GroupPermission::class);
    }

    /**
     * Check whether the group has a certain permission.
     *
     * @param string $permission
     * @return bool
     */
    public function hasPermission($permission)
    {
        if ($this->id == self::ADMINISTRATOR_ID) {
            return true;
        }

        return $this->permissions->contains('permission', $permission);
    }
}
