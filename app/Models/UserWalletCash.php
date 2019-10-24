<?php
declare (strict_types = 1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: UserWalletCash.php xxx 2019-10-22 16:32 zhouzhou $
 */

namespace App\Models;

use Discuz\Database\ScopeVisibilityTrait;
use Discuz\Foundation\EventGeneratorTrait;
use Illuminate\Database\Eloquent\Model;

class UserWalletCash extends Model
{
    use EventGeneratorTrait;
    use ScopeVisibilityTrait;

    /**
     * 与模型关联的数据表.
     *
     * @var string
     */
    protected $table = 'user_wallet_cash';

    /**
     * 该模型是否被自动维护时间戳.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * 模型的「启动」方法.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();
    }

    /**
     * 创建提现申请
     * @param  [type] $user_id        [description]
     * @param  [type] $user_wallet_id [description]
     * @param  [type] $cash_sn        [description]
     * @param  [type] $cash_charge    [description]
     * @param  [type] $cash_actual_amount    [description]
     * @param  [type] $cash_apply_amount     [description]
     * @param  [type] $remark         [description]
     * @return [type]                 [description]
     */
    public static function createCash(
        $user_id,
        $user_wallet_id,
        $cash_sn,
        $cash_charge,
        $cash_actual_amount,
        $cash_apply_amount,
        $remark) {
        $cash                     = new static;
        $cash->user_id            = $user_id;
        $cash->user_wallet_id     = $user_wallet_id;
        $cash->cash_sn            = $cash_sn;
        $cash->cash_charge        = $cash_charge;
        $cash->cash_actual_amount = $cash_actual_amount;
        $cash->cash_apply_amount  = $cash_apply_amount;
        $cash->remark             = $remark;
        $cash->cash_status        = 1; //待审核
        $cash->save();
        return $cash;
    }
}
