<?php

namespace App\Models;

use Discuz\Database\ScopeVisibilityTrait;
use Discuz\Foundation\EventGeneratorTrait;
use DomainException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property string $type
 * @property string $color
 * @property string $icon
 * @property Collection $users
 * @property Collection $permissions
 * @property int default
 * @method truncate()
 * @method create(array $array)
 * @method insert(array $array)
 * @method static find(int $GUEST_ID)
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
     * The ID of the guest group.
     */
    const GUEST_ID = 7;

    /**
     * The ID of the member group.
     */
    const MEMBER_ID = 10;

    const BAN_ID = 5;

    const DEFAULT = 1;

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = ['id', 'name', 'type', 'color', 'icon', 'default'];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function(self $group) {
            if (in_array($group->id, [self::GUEST_ID, self::ADMINISTRATOR_ID, self::MEMBER_ID])) {
                throw new DomainException('Cannot delete the default group');
            }
        });
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

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
