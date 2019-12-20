<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prototype extends Model
{
    /**
     * 与模型关联的数据表
     *
     * @var string
     */
    protected $table = 'circle';

    /**
     * 该模型是否被自动维护时间戳
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * 模型的日期字段的存储格式
     *
     * @var string
     */
    protected $dateFormat = 'U';

    /**
     * 存储时间戳的字段名
     *
     * @var string
     */
    const CREATED_AT = 'creation_date';

    /**
     * 存储时间戳的字段名
     *
     * @var string
     */
    const UPDATED_AT = 'last_update';

    /**
     * 可以被批量赋值的属性。
     *
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * 不可被批量赋值的属性。
     *
     * @var array
     */
    protected $guarded = ['price'];

    /**
     * 模型的「启动」方法
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        // 检索现有模型时触发
        static::retrieved(function () {
        });

        // 保存新模型前触发
        static::creating(function () {
        });

        // 保存新模型后触发
        static::created(function () {
        });

        // 修改模型前触发
        static::updating(function () {
        });

        // 修改模型后触发
        static::updated(function () {
        });

        // 创建或修改前触发
        static::saving(function () {
        });

        // 删除模型前触发
        static::deleting(function () {
        });

        // 删除模型后触发
        static::deleted(function () {
        });

        // 全局作用域
        // static::addGlobalScope();
    }
}
