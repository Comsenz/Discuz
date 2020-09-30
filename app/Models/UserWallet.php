<?php

/**
 * Copyright (C) 2020 Tencent Cloud.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace App\Models;

use Carbon\Carbon;
use Discuz\Database\ScopeVisibilityTrait;
use Discuz\Foundation\EventGeneratorTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $user_id
 * @property float $available_amount
 * @property float $freeze_amount
 * @property int $wallet_status
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property User $user
 * @method increment($column, $amount = 1, array $extra = [])
 * @method decrement($column, $amount = 1, array $extra = [])
 */
class UserWallet extends Model
{
    use EventGeneratorTrait;
    use ScopeVisibilityTrait;

    /**
     * 操作钱包
     */
    const OPERATE_INCREASE = 1;             // 增加操作

    const OPERATE_DECREASE = 2;             // 减少操作

    const OPERATE_INCREASE_FREEZE = 3;      // 增加冻结金额

    const OPERATE_DECREASE_FREEZE = 4;      // 减少冻结金额

    const OPERATE_FREEZE = 5;               // 冻结金额

    const OPERATE_UNFREEZE = 6;             // 解冻金额

    /**
     * 钱包状态
     */
    const WALLET_STATUS_NORMAL = 0; // 正常

    const WALLET_STATUS_FROZEN = 1; // 冻结提现

    /**
     * {@inheritdoc}
     */
    protected $primaryKey = 'user_id';

    /**
     * {@inheritdoc}
     */
    public $incrementing = false;

    /**
     * {@inheritdoc}
     */
    protected $casts = [
        'available_amount' => 'decimal:2',
        'freeze_amount' => 'decimal:2',
    ];

    /**
     * 创建用户钱包
     *
     * @param int $user_id 用户ID
     * @return UserWallet
     */
    public static function createUserWallet($user_id)
    {
        $wallet = new static;
        $wallet->user_id = $user_id;
        $wallet->available_amount = 0.00;
        $wallet->freeze_amount = 0.00;
        $wallet->wallet_status = 0;

        $wallet->save();

        return $wallet;
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
    public function userWalletCash()
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
