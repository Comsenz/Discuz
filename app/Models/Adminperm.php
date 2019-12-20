<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Adminperm extends Model
{
    public $timestamps = false;

    protected $keys = 'example_key';

    protected $fillable = ['id', 'name','perm','createtime','updatetime'];
}
