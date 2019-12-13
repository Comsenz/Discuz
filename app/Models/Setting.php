<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * @method static create(array $array)
 * @method static updateOrCreate(array $array)
 * @method truncate()
 * @method static insert()
 */
class Setting extends Model
{
    protected $fillable = ['key', 'value', 'tag'];
    protected $primaryKey = ['key', 'tag'];
    protected $keyType = 'string';
    public $incrementing = false;

    public $timestamps = false;

}
