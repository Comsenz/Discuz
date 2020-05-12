<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GroupPaidUser extends Model
{
    use SoftDeletes;

    protected $fillable = ['delete_type'];
    /**
     * 删除类型，到期删除
     */
    const DELETE_TYPE_EXPIRE   = 1;
    /**
     * 删除类型，管理员修改
     */
    const DELETE_TYPE_ADMIN    = 2;
    /**
     * 删除类型，用户复购
     */
    const DELETE_TYPE_RENEW    = 3;

    public static function creation(
        $user_id,
        $group_id,
        $expiration_time,
        $order_id,
        $operator_id,
        $delete_type = 0
    ) {
        // 实例一个模型
        $group_paid_user = new static;

        $group_paid_user->user_id = $user_id;
        $group_paid_user->group_id = $group_id;
        $group_paid_user->expiration_time = $expiration_time;
        $group_paid_user->order_id = $order_id;
        $group_paid_user->operator_id = $operator_id;
        $group_paid_user->delete_type = $delete_type;
        // 返回模型
        return $group_paid_user;
    }
}
