<?php


namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property $uid
 * @property $sum_score
 * @method static where($key, $val)
 * @method static create(array $array)
 * @method static updateOrCreate(array $array, array $where)
 * @method static find()
 * @method truncate()
 * @method insert(array $array)
 */
class UserCreditScoreStatistics extends Model
{
    /**
     * {@inheritdoc}
     */
    protected $table = 'user_credit_score_statistics';

    /**
     * {@inheritdoc}
     */
    protected $dates = [
        'updated_at',
        'created_at',
    ];

    /**
     * @var array
     */
    protected $fillable = [
        'uid',
        'sum_score'
    ];

    /**
     * 写入统计数据
     * @param $uid
     * @param $sumScore
     */
    public static function statics($uid, $sumScore)
    {
        $stat = new static;

    }
}
