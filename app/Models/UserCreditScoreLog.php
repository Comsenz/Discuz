<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property $uid
 * @property $rid
 * @method static where($key, $val)
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
        'rid'
    ];

    public static function build($uid, $rid)
    {
        $log = new static;
        $log->uid = $uid;
        $log->rid = $rid;
        return $log;
    }



}
