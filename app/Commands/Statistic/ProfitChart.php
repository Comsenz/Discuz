<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Commands\Statistic;

use App\Models\Finance;
use App\Models\User;
use App\Repositories\FinanceRepository;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Auth\Exception\PermissionDeniedException;

class ProfitChart
{
    use AssertPermissionTrait;

    protected $actor;

    protected $type;

    protected $createdAtBegin;

    protected $createdAtEnd;

    protected $finance;

    public function __construct(User $actor, $type, $createdAtBegin, $createdAtEnd)
    {
        $this->actor            = $actor;
        $this->type             = $type;
        $this->createdAtBegin   = $createdAtBegin;
        $this->createdAtEnd     = $createdAtEnd;
    }

    public function handle(FinanceRepository $finance)
    {
        $this->finance = $finance;

        return call_user_func([$this, '__invoke']);
    }

    /**
     * @return mixed
     * @throws PermissionDeniedException
     */
    public function __invoke()
    {
        $this->assertAdmin($this->actor);

        $query = $this->finance->query();
        $query->whereBetween('created_at', [$this->createdAtBegin, $this->createdAtEnd]);

        if ($this->type !== Finance::TYPE_DAYS) {
            $format = '';
            if ($this->type == Finance::TYPE_WEEKS) {
                $format = '%Y-%u';
            } elseif ($this->type == Finance::TYPE_MONTH) {
                $format = '%Y-%m';
            }
            $query->selectRaw(
                "DATE_FORMAT(created_at,'{$format}') as `date`,".
                'SUM(order_count) as order_count,'.
                'SUM(order_amount) as order_amount,'.
                'SUM(total_profit) as total_profit,'.
                'SUM(register_profit) as register_profit,'.
                'SUM(master_portion) as master_portion,'.
                'SUM(withdrawal_profit) as withdrawal_profit'
            );
            $query->groupBy('date');
            $query->orderBy('date', 'asc');
        }

        return $query->get();
    }
}
