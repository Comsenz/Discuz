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
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $from_user_id
 * @property int $to_user_id
 * @property int $is_mutual
 * @property Carbon $updated_at
 * @property Carbon $created_at
 * @package App\Models
 */
class UserFollow extends Model
{
    const MUTUAL = 1; //相互关注

    const NOT_MUTUAL = 0; //没有相互关注

    /**
     * {@inheritdoc}
     */
    protected $table = 'user_follow';

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
        'from_user_id',
        'to_user_id',
        'is_mutual',
    ];

    /**
     * Define the relationship with the from_user.
     *
     * @return belongsTo
     */
    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    /**
     * Define the relationship with the to_user.
     *
     * @return belongsTo
     */
    public function toUser()
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }
}
