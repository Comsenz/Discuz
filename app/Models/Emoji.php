<?php
declare(strict_types=1);

namespace App\Models;

use Discuz\Database\ScopeVisibilityTrait;
use Discuz\Foundation\EventGeneratorTrait;
use Illuminate\Database\Eloquent\Model;

class Emoji extends Model
{
    use EventGeneratorTrait;
    use ScopeVisibilityTrait;

    /**
     * 与模型关联的数据表.
     *
     * @var string
     */
    protected $table = 'emojies';

    /**
     * 该模型是否被自动维护时间戳.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * 存储时间戳的字段名
     *
     * @var string
     */
    const UPDATED_AT = 'updated_at';


    /**
     * 模型的「启动」方法.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();
    }
}