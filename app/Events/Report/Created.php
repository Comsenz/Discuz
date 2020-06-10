<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Events\Report;

use App\Models\Report;
use App\Models\User;

class Created
{
    /**
     * @var Report
     */
    public $report;

    /**
     * @var User
     */
    public $actor;

    /**
     * Created constructor.
     *
     * @param Report $report
     * @param null $actor
     */
    public function __construct(Report $report, $actor = null)
    {
        $this->report = $report;
        $this->actor = $actor;
    }
}
