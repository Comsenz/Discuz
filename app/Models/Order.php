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
use Closure;
use Discuz\Contracts\Setting\SettingsRepository;
use Discuz\Database\ScopeVisibilityTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property string $order_sn
 * @property string $payment_sn
 * @property float $amount
 * @property float $master_amount
 * @property float $author_amount
 * @property int $be_scale
 * @property int $user_id
 * @property int $payee_id
 * @property int $type
 * @property int $thread_id
 * @property int $group_id
 * @property int $status
 * @property int $platform
 * @property int $payment_type
 * @property int $is_anonymous
 * @property string $remark
 * @property Carbon $expired_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Thread $thread
 * @property User $user
 * @property User $payee
 * @package App\Models
 */
class Order extends Model
{
    use ScopeVisibilityTrait;

    /**
     * 订单类型
     */
    const ORDER_TYPE_REGISTER = 1; //注册

    const ORDER_TYPE_REWARD   = 2; //打赏

    const ORDER_TYPE_THREAD   = 3; //付费主题

    const ORDER_TYPE_GROUP    = 4; //付费用户组

    /**
     * 订单状态
     */
    const ORDER_STATUS_PENDING = 0; //待付款

    const ORDER_STATUS_PAID    = 1; //已付款

    const ORDER_STATUS_CANCEL  = 2; //取消订单

    const ORDER_STATUS_FAILED  = 3; //支付失败

    const ORDER_STATUS_EXPIRED = 4; //订单已过期

    /**
     * 注册收款人ID
     */
    const REGISTER_PAYEE_ID = 0;

    /**
     * 付款方式
     */
    const PAYMENT_TYPE_WECHAT_NATIVE = 10; //微信扫码支付

    const PAYMENT_TYPE_WECHAT_WAP    = 11; //微信h5支付

    const PAYMENT_TYPE_WECHAT_JS     = 12; //微信网页、公众号

    const PAYMENT_TYPE_WECHAT_MINI   = 13; //微信小程序支付

    const PAYMENT_TYPE_WALLET        = 20;//钱包支付

    /**
     * 订单过期时间，单位分钟，订单过期后无法支付
     */
    const ORDER_EXPIRE_TIME          = 10;

    /**
     * {@inheritdoc}
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'master_amount' => 'decimal:2',
        'type' => 'integer',
        'status' => 'integer',
        'is_anonymous' => 'boolean',
    ];

    /**
     * 订单类型
     * 1：注册，2：打赏，3：付费主题，4：付费用户组
     *
     * @var array
     */
    public static $enumType = [
        1 => '注册',
        2 => '打赏',
        3 => '付费主题',
        4 => '付费用户组',
    ];

    /**
     * 订单类型 - 枚举
     *
     * @param $mixed
     * @param mixed $default 枚举值/闭包
     * @return bool|false|int|mixed|string|callback
     */
    public static function enumType($mixed, $default = null)
    {
        $enum = static::$enumType;

        if (is_numeric($mixed)) {
            if ($bool = array_key_exists($mixed, $enum)) {
                // 获取对应value值
                $trans = $enum[$mixed];
            }
        } elseif (is_string($mixed)) {
            if ($bool = in_array($mixed, $enum)) {
                // 获取对应key值
                $trans = array_search($mixed, $enum);
            }
        } else {
            return false;
        }

        if (!isset($trans)) {
            return false;
        }

        if (empty($default)) {
            $result = $trans;
        } elseif ($default instanceof Closure) {
            $result = $default(['key' => $mixed, 'value' => $trans, 'bool' => $bool]);
        } else {
            $result = $bool;
        }

        return $result;
    }

    /**
     * 判断是否是分成的订单
     *
     * @return bool
     */
    public function isScale()
    {
        return $this->be_scale > 0;
    }

    /**
     * 计算去掉站长、上级的作者实际金额数
     *
     * @param bool $getAuthorAmount 获取作者金额数
     * @return float $bossAmount 上级实际分成金额数
     */
    public function calculateAuthorAmount($getAuthorAmount = false)
    {
        /**
         * 获取 站长->作者 分成
         * ( 注册付费站点时 master_amount 是0 )
         */
        $actualAmount = number_format($this->amount - $this->master_amount, 2, '.', '');

        $bossAmount = 0;
        // 计算 作者->上级 分成
        if ($this->isScale()) {
            $beScale = $this->be_scale / 10;

            // 上级实际分到金额
            $bossAmount = number_format($actualAmount * $beScale, 2, '.', '');
            // 去掉上级分成 作者实际得到金额
            $actualAmount = number_format($actualAmount - $bossAmount, 2, '.', '');
        }

        $this->author_amount = $actualAmount;

        return $getAuthorAmount ?  $this->author_amount : $bossAmount ;
    }

    /**
     * 计算站长和作者实际金额数
     *
     * @return float $bossAmount 上级实际分成金额数
     */
    public function calculateMasterAmount()
    {
        $settings = app(SettingsRepository::class);

        // 站长作者分成配置
        $site_author_scale = $settings->get('site_author_scale');

        $order_amount = $this->amount; // 订单金额
        $author_ratio = $site_author_scale / 10;

        $payee_amount = sprintf('%.2f', ($order_amount * $author_ratio));
        $this->master_amount = $order_amount - $payee_amount; // 站长分成金额

        return $bossAmount = $this->calculateAuthorAmount(); // 作者实际分成金额
    }

    /**
     * Define the relationship with the order's owner.
     *
     * @return belongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Define the relationship with the order's payee.
     *
     * @return belongsTo
     */
    public function payee()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Define the relationship with the order's pay_notify.
     *
     * @return hasOne
     */
    public function payNotify()
    {
        return $this->hasOne(PayNotify::class, 'payment_sn', 'payment_sn');
    }

    /**
     * Define the relationship with the order's thread.
     *
     * @return BelongsTo
     */
    public function thread()
    {
        return $this->belongsTo(Thread::class);
    }

    /**
     * Define the relationship with the order's thread.
     *
     * @return BelongsTo
     */
    public function group()
    {
        return $this->belongsTo(Group::class);
    }
}
