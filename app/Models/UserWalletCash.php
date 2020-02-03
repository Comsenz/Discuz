<?php


/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
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
     * 提现状态
     */
    const STATUS_REVIEW          = 1; //待审核

    const STATUS_REVIEWED        = 2; //审核通过

    const STATUS_REVIEW_FAILED   = 3; //审核不通过

    const STATUS_IN_PAYMENT      = 4; //待打款

    const STATUS_PAID            = 5; //已打款

    const STATUS_PAYMENT_FAILURE = 6; //打款失败

    /**
     * 返款状态
     */
    const REFUNDS_STATUS_NO  = 0; //未返款

    const REFUNDS_STATUS_YES = 1; //已返款

    /**
     * 创建提现申请
     * @param  [type] $user_id        [description]
     * @param  [type] $cash_sn        [description]
     * @param  [type] $cash_charge    [description]
     * @param  [type] $cash_actual_amount    [description]
     * @param  [type] $cash_apply_amount     [description]
     * @param  [type] $remark         [description]
     * @return [type]                 [description]
     */
    public static function createCash(
        $user_id,
        $cash_sn,
        $cash_charge,
        $cash_actual_amount,
        $cash_apply_amount,
        $remark
    ) {
        $cash                     = new static;
        $cash->user_id            = $user_id;
        $cash->cash_sn            = $cash_sn;
        $cash->cash_charge        = $cash_charge;
        $cash->cash_actual_amount = $cash_actual_amount;
        $cash->cash_apply_amount  = $cash_apply_amount;
        $cash->remark             = $remark;
        $cash->trade_no           = '';
        $cash->error_code         = '';
        $cash->error_message      = '';
        $cash->cash_status        = 1; //待审核
        $cash->save();
        return $cash;
    }

    /**
     * Define the relationship with the cash record's creator.
     *
     * @return belongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Define the relationship with the cash record's wallet.
     *
     * @return belongsTo
     */
    public function userWallet()
    {
        return $this->belongsTo(UserWallet::class, 'user_id', 'user_id');
    }
}
