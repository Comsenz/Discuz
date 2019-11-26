<?php

namespace App\Models;

use Discuz\Database\ScopeVisibilityTrait;
use Discuz\Foundation\EventGeneratorTrait;
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

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = ['name', 'type', 'color', 'icon'];

    /**
     * Define the relationship with the group's permissions.
     *
     * @return HasMany
     */
    public function groupPermission()
    {
        return $this->hasMany(GroupPermission::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
