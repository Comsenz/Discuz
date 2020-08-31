<?php


namespace App\Repositories;

use App\Models\UserCreditScoreLog;
use Discuz\Foundation\AbstractRepository;
use Illuminate\Database\Eloquent\Model;

class UserCreditScoreLogRepository extends AbstractRepository
{
    /**
     * Get a new query builder for the posts table.
     *
     * @return Model|\Illuminate\Database\Eloquent\Builder
     */
    public static function query()
    {
        return UserCreditScoreLog::query();
    }

    public static function build(array $data)
    {
        $log = new static;
        $log->attributes = $data;
        return $log;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }




}
