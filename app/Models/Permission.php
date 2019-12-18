<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * @method static where(string $string, $permission)
 * @method static insert(array $array_map)
 */
class Permission extends Model
{
    public $timestamps = false;

    protected $fillable = ['group_id', 'permission'];

    protected $table = 'group_permission';
}
