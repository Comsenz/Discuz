<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Group extends Model
{

    const GUEST_ID = 7;

    const ADMINISTRATOR_ID = 1;

    const MEMBER_ID = 10;


    public $timestamps = false;

    protected $fillable = ['name', 'type', 'color', 'icon'];


}
