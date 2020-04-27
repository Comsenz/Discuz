<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Events\Report;

use App\Models\Report;
use App\Models\User;

class Saving
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
     * 用户输入的参数.
     *
     * @var array
     */
    public $data;

    /**
     * @param Report $report
     * @param User $actor
     * @param array $data
     */
    public function __construct(Report $report, User $actor = null, array $data = [])
    {
        $this->report = $report;
        $this->actor = $actor;
        $this->data = $data;
    }
}
