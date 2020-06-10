<?php


/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Models;

use Carbon\Carbon;
use Closure;
use Discuz\Database\ScopeVisibilityTrait;
use Discuz\Foundation\EventGeneratorTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class UserWalletCash
 *
 * @package App\Models
 * @property User user
 * @property int id
 * @property int user_id
 * @property int cash_sn
 * @property float cash_charge
 * @property float cash_actual_amount
 * @property float cash_apply_amount
 * @property string trade_no
 * @property string error_code
 * @property string error_message
 * @property int cash_status
 * @property string remark
 * @property Carbon $created_at
 */
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
     * 提现状态：
     * 1：待审核，2：审核通过，3：审核不通过，4：待打款， 5，已打款， 6：打款失败
     * @var array
     */
    public static $enumCashStatus = [
        1 => '待审核',
        2 => '审核通过',
        3 => '审核不通过',
        4 => '待打款',
        5 => '已打款',
        6 => '打款失败',
    ];

    /**
     * 提现状态
     */
    const STATUS_REVIEW = 1; //待审核

    const STATUS_REVIEWED = 2; //审核通过

    const STATUS_REVIEW_FAILED = 3; //审核不通过

    const STATUS_IN_PAYMENT = 4; //待打款

    const STATUS_PAID = 5; //已打款

    const STATUS_PAYMENT_FAILURE = 6; //打款失败

    /**
     * 返款状态
     */
    const REFUNDS_STATUS_NO = 0; //未返款

    const REFUNDS_STATUS_YES = 1; //已返款

    /**
     * 创建提现申请
     * @param $user_id
     * @param $cash_sn
     * @param $cash_charge
     * @param $cash_actual_amount
     * @param $cash_apply_amount
     * @param $remark
     * @return UserWalletCash [type]                 [description]
     */
    public static function createCash(
        $user_id,
        $cash_sn,
        $cash_charge,
        $cash_actual_amount,
        $cash_apply_amount,
        $remark
    ) {
        $cash = new static;
        $cash->user_id = $user_id;
        $cash->cash_sn = $cash_sn;
        $cash->cash_charge = $cash_charge;
        $cash->cash_actual_amount = $cash_actual_amount;
        $cash->cash_apply_amount = $cash_apply_amount;
        $cash->remark = $remark;
        $cash->trade_no = '';
        $cash->error_code = '';
        $cash->error_message = '';
        $cash->cash_status = 1; // 待审核
        $cash->save();
        return $cash;
    }

    /**
     * 提现状态 - 枚举
     *
     * @param $mixed
     * @param mixed $default 枚举值/闭包
     * @return bool|false|int|mixed|string|callback
     */
    public static function enumCashStatus($mixed, $default = null)
    {
        $enum = static::$enumCashStatus;

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
     * 获取对应种类的通知
     *
     * @param $cashStatus
     * @param callable|null $callback
     * @return bool|callable|mixed
     */
    public static function notificationByWhich($cashStatus, callable $callback = null)
    {
        // 1：待审核，2：审核通过，3：审核不通过，4：待打款， 5，已打款， 6：打款失败
        if (is_null($callback)) {
            if (in_array($cashStatus, [1, 2, 4, 5])) {
                return true; // 是正常通知状态
            }

            return false;   // 是失败通知
        } else {
            return $callback([
                'normal' => [1, 2, 4, 5], // 正常通知状态
                'fail' => [3, 6],         // 失败通知
                'send' => [2, 3],         // 允许发送的通知
            ]);
        }
    }

    /**
     * Define the relationship with the cash record's creator.
     *
     * @return BelongsTo
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
