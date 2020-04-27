<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Models;

use App\Events\Report\Created;
use Carbon\Carbon;
use Discuz\Database\ScopeVisibilityTrait;
use Discuz\Foundation\EventGeneratorTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * @package App\Models
 *
 * @property int $user_id
 * @property int $thread_id
 * @property int $post_id
 * @property int $type
 * @property int $status
 * @property string $reason
 * @property Carbon updated_at
 * @property Carbon created_at
 * @method static create(array $array)
 */
class Report extends Model
{
    use EventGeneratorTrait;
    use ScopeVisibilityTrait;

    /**
     * Create a new report
     *
     * @param int $user_id
     * @param int $thread_id
     * @param int $post_id
     * @param int $type
     * @param string $reason
     * @param int $status
     * @return static
     */
    public static function build(int $user_id, int $thread_id, int $post_id, int $type, string $reason, int $status = 0)
    {
        $report = new static;

        $report->user_id = $user_id;
        $report->thread_id = $thread_id;
        $report->post_id = $post_id;
        $report->type = $type;
        $report->reason = $reason;
        $report->status = $status;

        $report->raise(new Created($report));

        return $report;
    }
}
