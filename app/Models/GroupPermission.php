<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Models;

use App\Events\GroupPermission\Created;
use Discuz\Database\ScopeVisibilityTrait;
use Discuz\Foundation\EventGeneratorTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $group_id
 * @property string $permission
 */
class GroupPermission extends Model
{
    use EventGeneratorTrait;
    use ScopeVisibilityTrait;

    /**
     * @var string
     */
    protected $table = 'group_permission';

    /**
     * @var array
     */
    protected $fillable = ['group_id', 'permission'];

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @param int $group_id
     * @param string $permission
     * @return static
     */
    public static function creation($group_id, $permission)
    {
        $groupPermission = new static;

        $groupPermission->group_id = $group_id;
        $groupPermission->permission = $permission;

        $groupPermission->raise(new Created($groupPermission));

        return $groupPermission;
    }
}
