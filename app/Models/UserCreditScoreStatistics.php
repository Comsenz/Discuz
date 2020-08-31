<?php


namespace App\Models;

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

    public static function build(array $data)
    {
        $stat = new static;
        $stat->attributes = $data;
        return $stat;
    }
}
