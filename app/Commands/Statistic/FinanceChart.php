<?php

/**
 * Copyright (C) 2020 Tencent Cloud.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace App\Commands\Statistic;

use App\Models\Finance;
use App\Models\User;
use App\Repositories\FinanceRepository;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Auth\Exception\PermissionDeniedException;

class FinanceChart
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
        $this->assertCan($this->actor, 'statistic.financeChart');

        $query = $this->finance->query();
        $query->whereBetween('created_at', [$this->createdAtBegin, $this->createdAtEnd]);

        if ($this->type !== Finance::TYPE_DAYS) {
            $format = '';
            if ($this->type == Finance::TYPE_WEEKS) {
                $format = '%Y/%u'.app('translator')->get('statistic.week');
            } elseif ($this->type == Finance::TYPE_MONTH) {
                $format = '%Y/%m'.app('translator')->get('statistic.month');
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
        } else {
            $query->selectRaw("*, DATE_FORMAT(created_at,'%Y/%m/%d') as `date` ");
        }

        return $query->get();
    }
}
