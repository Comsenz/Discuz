<?php


namespace App\Models;


use Discuz\Database\ScopeVisibilityTrait;
use Discuz\Foundation\EventGeneratorTrait;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use EventGeneratorTrait;
    use ScopeVisibilityTrait;

    const GUEST_ID = 7;

    const ADMINISTRATOR_ID = 1;

    const MEMBER_ID = 10;


    public $timestamps = false;

    protected $fillable = ['name', 'type', 'color', 'icon'];

    public function groupPermission()
    {
        return $this->hasMany(GroupPermission::class);
    }
}
