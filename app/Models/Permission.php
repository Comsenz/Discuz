<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    public $timestamps = false;

    protected $fillable = ['group_id', 'permission'];

    protected $table = 'group_permission';

}
