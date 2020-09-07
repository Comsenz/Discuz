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
 * @package App\Models
 *
 * @property int $id
 * @property int $thread_id
 * @property int $user_id
 * @property int $be_user_id
 * @property float $price
 * @property float $onlooker_unit_price
 * @property float $onlooker_price
 * @property int $onlooker_number
 * @property bool $is_onlooker
 * @property int $is_answer
 * @property Carbon created_at
 * @property Carbon updated_at
 * @property Carbon expired_at
 * @property User $user
 * @property User $beUserId
 * @property UserWalletLog $userWalletLog
 */
class Question extends Model
{
    const EXPIRED_DAY = 7; // 过期时间 (day)

    const TYPE_ANSWER_UNANSWERED = 0; // 未回答

    const TYPE_ANSWER_ANSWERED = 1; // 已回答

    const TYPE_ANSWER_EXPIRED = 2; // 已过期

    protected $fillable = [
        'thread_id',
        'user_id',
        'be_user_id',
        'price',
        'onlooker_unit_price',
        'onlooker_price',
        'onlooker_number',
        'is_onlooker',
        'is_answer',
        'expired_at',
    ];

    /**
     * {@inheritdoc}
     */
    protected $casts = [
        'is_onlooker' => 'boolean',
    ];

    /**
     * {@inheritdoc}
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /**
     * Create a new self
     *
     * @param array $attributes
     * @return static
     */
    public static function build(array $attributes)
    {
        $self = new static;

        $self->fill($attributes);

        return $self;
    }

    /**
     * Define the relationship with the Question's author.
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
    public function beUserId()
    {
        return $this->belongsTo(User::class, 'be_user_id', 'id');
    }

    public function userWalletLog()
    {
        return $this->belongsTo(UserWalletLog::class, 'id', 'question_id');
    }
}
