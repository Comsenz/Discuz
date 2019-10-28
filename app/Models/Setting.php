<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'tag'];
    protected $primaryKey = ['key', 'tag'];
    protected $keyType = 'string';
    public $incrementing = false;

    public $timestamps = false;

}
