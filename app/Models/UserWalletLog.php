<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Models;

use Discuz\Database\ScopeVisibilityTrait;
use Discuz\Foundation\EventGeneratorTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $user_id
 * @property float $change_available_amount
 * @property float $change_freeze_amount
 * @property int $change_type
 * @property string $change_desc
 * @property int $order_id
 * @property int $user_wallet_cash_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @package App\Models
 */
class UserWalletLog extends Model
{
    use EventGeneratorTrait;
    use ScopeVisibilityTrait;

    /**
     * {@inheritdoc}
     */
    protected $table = 'user_wallet_logs';

    /**
     * 钱包明细类型
     */
    const TYPE_CASH_FREEZE = 10; //提现冻结

    const TYPE_CASH_SUCCESS = 11; //提现成功

    const TYPE_CASH_THAW    = 12; //提现解冻

    const TYPE_INCOME_REGISTER   = 30; //注册收入

    const TYPE_INCOME_REWARD     = 31; //打赏收入

    const TYPE_INCOME_ARTIFICIAL = 32; //人工收入

    const TYPE_EXPEND_ARTIFICIAL = 50; //人工支出

    // ----- 分割线 -----

    const TYPE_EXPEND_REWARD     = 41; //打赏支出

    const TYPE_INCOME_THREAD     = 60; //付费主题收入

    const TYPE_EXPEND_THREAD     = 61; //付费主题支出

    const TYPE_EXPEND_RENEW      = 71; //站点续费支出

    /**
     * 创建钱包动账记录
     *
     * @param int $user_id
     * @param float $change_available_amount
     * @param float $change_freeze_amount
     * @param int $change_type
     * @param string $change_desc
     * @param int|null $user_wallet_cash_id
     * @param int|null $order_id
     * @return UserWalletLog
     */
    public static function createWalletLog(
        $user_id,
        $change_available_amount,
        $change_freeze_amount,
        $change_type,
        $change_desc,
        $user_wallet_cash_id = null,
        $order_id = null
    ) {
        $wallet_log                          = new static;
        $wallet_log->user_id                 = $user_id;
        $wallet_log->change_available_amount = $change_available_amount;
        $wallet_log->change_freeze_amount    = $change_freeze_amount;
        $wallet_log->change_type             = $change_type;
        $wallet_log->change_desc             = $change_desc;
        $wallet_log->user_wallet_cash_id     = $user_wallet_cash_id;
        $wallet_log->order_id     = $order_id;

        $wallet_log->save();
        return $wallet_log;
    }

    /**
     * Define the relationship with the log's wallet.
     *
     * @return belongsTo
     */
    public function userWallet()
    {
        return $this->belongsTo(UserWallet::class, 'user_id', 'user_id');
    }

    /**
     * Define the relationship with the wallet log's owner.
     *
     * @return belongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Define the relationship with the cash log.
     *
     * @return belongsTo
     */
    public function userWalletCash()
    {
        return $this->belongsTo(UserwalletCash::class);
    }

    /**
     * Define the relationship with the log order.
     *
     * @return belongsTo
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
