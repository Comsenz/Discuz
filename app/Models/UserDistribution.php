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

use Carbon\Carbon;
use Discuz\Database\ScopeVisibilityTrait;
use Discuz\Foundation\EventGeneratorTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @package App\Models
 *
 * @property int $id
 * @property int $user_id
 * @property int $pid
 * @property int $be_scale
 * @property int $level
 * @property string $invites_code
 * @property int $is_subordinate
 * @property int $is_commission
 * @property Carbon updated_at
 * @property Carbon created_at
 * @property User $parentUser
 */
class UserDistribution extends Model
{
    use EventGeneratorTrait;
    use ScopeVisibilityTrait;

    protected $fillable = [
        'pid',
        'user_id',
        'invites_code',
        'be_scale',
        'level',
        'is_subordinate',
        'is_commission',
    ];

    /**
     * Create a new user distribute
     *
     * @param array $attributes
     * @return static
     */
    public static function build(array $attributes)
    {
        $distribute = new static;

        $distribute->fill($attributes);

        return $distribute;
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

    /**
     * @return BelongsTo
     */
    public function parentUser()
    {
        return $this->belongsTo(User::class, 'pid', 'id');
    }
}
