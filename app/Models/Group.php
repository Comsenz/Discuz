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
 * @property Collection $users
 * @property Collection $permissions
 * @property int default
 * @method truncate()
 * @method create(array $array)
 * @method insert(array $array)
 * @method static find(mixed $GUEST_ID)
 * @method static where(string $string, int $id)
 */
class Group extends Model
{
    use EventGeneratorTrait, ScopeVisibilityTrait;

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
     * @var bool
     */
    public $timestamps = false;

    /**
     * {@inheritdoc}
     */
    protected $casts = [
        'default' => 'boolean',
    ];

    /**
     * @var array
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
     * Define the relationship with the group's permissions.
     *
     * @return HasMany
     */
    public function permission()
    {
        return $this->hasMany(GroupPermission::class);
    }

    /**
     * @return BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
