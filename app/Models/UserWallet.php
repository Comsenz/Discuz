<?php
declare (strict_types = 1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: UserWallet.php xxx 2019-10-22 16:32 zhouzhou $
 */

namespace App\Models;

use Discuz\Database\ScopeVisibilityTrait;
use Discuz\Foundation\EventGeneratorTrait;
use Illuminate\Database\Eloquent\Model;

class UserWallet extends Model
{
    use EventGeneratorTrait;
    use ScopeVisibilityTrait;

    /**
     * 与模型关联的数据表.
     *
     * @var string
     */
    protected $table = 'user_wallet';

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
     * 创建用户钱包
     * @param  int $user_id 用户ID
     * @return UserWallet
     */
    public static function createUserWallet($user_id)
    {
        $user_wallet                   = new static;
        $user_wallet->user_id          = $user_id;
        $user_wallet->available_amount = 0.00;
        $user_wallet->freeze_amount    = 0.00;
        $user_wallet->wallet_status    = 0;
        $user_wallet->save();
        return $user_wallet;
    }
}
