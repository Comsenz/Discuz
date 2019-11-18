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
     * 主键
     * @var string
     */
    protected $primaryKey = 'user_id';

    /**
     * 非递增主键
     * @var boolean
     */
    public $incrementing = false;

    /**
     * 该模型是否被自动维护时间戳.
     *
     * @var bool
     */
    public $timestamps = true;

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

    /**
     * Define the relationship with the wallet's owner.
     *
     * @return belongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Define the relationship with the user wallet's cashes.
     *
     * @return hasMany
     */
    public function userWallet()
    {
        return $this->hasMany(UserWalletCash::class, 'user_id', 'user_id');
    }

    /**
     * Define the relationship with the user wallet's logs.
     *
     * @return hasMany
     */
    public function userWalletLog()
    {
        return $this->hasMany(UserWalletLog::class, 'user_id', 'user_id');
    }

}
