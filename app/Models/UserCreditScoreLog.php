<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

    public static function build(array $data)
    {
        $log = new static;
        $log->attributes = $data;
        return $log;
    }





}
