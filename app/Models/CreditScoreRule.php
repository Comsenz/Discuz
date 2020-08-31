<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;



/**
 * @property $rule
 * @property $action
 * @property $cycle_type
 * @property $interval_time
 * @property $reward_num
 * @property $score
 * @method static where($key, $val)
 * @method static create(array $array)
 * @method static updateOrCreate(array $array)
 * @method truncate()
 * @method insert(array $array)
 */
class CreditScoreRule extends Model
{
    /**
     * {@inheritdoc}
     */
    protected $table = 'credit_score_rule';

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
        'rule',
        'action',
        'cycle_type',
        'interval_time',
        'reward_num',
        'score'
    ];

}
