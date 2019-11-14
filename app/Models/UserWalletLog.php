<?php
declare (strict_types = 1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: UserWalletLog.php xxx 2019-10-22 16:33 zhouzhou $
 */

namespace App\Models;

use Discuz\Database\ScopeVisibilityTrait;
use Discuz\Foundation\EventGeneratorTrait;
use Illuminate\Database\Eloquent\Model;

class UserWalletLog extends Model
{
    use EventGeneratorTrait;
    use ScopeVisibilityTrait;

    /**
     * 与模型关联的数据表.
     *
     * @var string
     */
    protected $table = 'user_wallet_log';

    /**
     * 该模型是否被自动维护时间戳.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * 钱包明细类型
     */
    const TYPE_CASH_SFREEZE = 10;//提现冻结
    const TYPE_CASH_SUCCESS = 11;//提现成功
    const TYPE_CASH_THAW = 12;//提现解冻

    const TYPE_INCOME_REWARD = 31;//打赏收入
    const TYPE_INCOME_ARTIFICIAL = 32;//人工收入
     
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
     * 创建钱包动账记录
     * @param  [type] $user_id                 [description]
     * @param  [type] $change_available_amount [description]
     * @param  [type] $change_freeze_amount    [description]
     * @param  [type] $change_type             [description]
     * @param  [type] $change_desc             [description]
     * @return [type]                          [description]
     */
    public static function createWalletLog(
        $user_id,
        $change_available_amount,
        $change_freeze_amount,
        $change_type,
        $change_desc) {
        $wallet_log                          = new static;
        $wallet_log->user_id                 = $user_id;
        $wallet_log->change_available_amount = $change_available_amount;
        $wallet_log->change_freeze_amount    = $change_freeze_amount;
        $wallet_log->change_type             = $change_type;
        $wallet_log->change_desc             = $change_desc;

        $wallet_log->save();
        return $wallet_log;
    }
}
