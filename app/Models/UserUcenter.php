<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class UserUcenter extends Model
{
    protected $fillable = ['user_id', 'ucenter_id'];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
