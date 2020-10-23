<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property $uid
 * @property $rid
 * @property $cycle_type
 * @property $interval_time
 * @property $reward_num
 * @method static create(array $array)
 * @method truncate()
 * Class UserCreditScoreLog
 * @package App\Models
 */
class UserCreditScoreLog extends Model
{
    /**
     * {@inheritdoc}
     */
    protected $table = 'user_credit_score_log';

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
        'rid',
        'cycle_type',
        'interval_time',
        'reward_num',
        'score',
        'type'
    ];



    /**
     * Define the relationship with the order's owner.
     *
     * @return belongsTo
     */
    public function rule()
    {
        return $this->belongsTo(CreditScoreRule::class, 'rid');
    }


}
