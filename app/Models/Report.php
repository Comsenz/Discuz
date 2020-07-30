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

namespace App\Models;

use App\Events\Report\Created;
use Carbon\Carbon;
use Discuz\Database\ScopeVisibilityTrait;
use Discuz\Foundation\EventGeneratorTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    /**
     * Define the relationship with the report's author.
     *
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
