<?php


namespace App\Repositories;

use App\Models\UserCreditScoreLog;
use Discuz\Foundation\AbstractRepository;
use Illuminate\Database\Eloquent\Builder;

class UserCreditScoreLogRepository extends AbstractRepository
{

    protected $table;

    public function __construct(UserCreditScoreLog $log)
    {
        $this->table = $log;
    }

    /**
     * Get a new query builder for the users table.
     *
     * @return Builder
     */
    public function query()
    {
        return UserCreditScoreLog::query();
    }

    public function insert($uid, $rid)
    {
        $this->table->uid = $uid;
        $this->table->rid = $rid;
        $this->table->saveOrFail();
    }

    public function save($data)
    {

    }


}
