<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $group_id
 * @property string $permission
 */
class Permission extends Model
{
    /**
     * {@inheritdoc}
     */
    protected $table = 'group_permission';

    /**
     * {@inheritdoc}
     */
    protected $fillable = ['group_id', 'permission'];

    /**
     * {@inheritdoc}
     */
    public $timestamps = false;

    /**
     * Define the relationship with the group that this permission is for.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function group()
    {
        return $this->belongsTo(Group::class);
    }
}
