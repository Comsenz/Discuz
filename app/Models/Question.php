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

use App\Events\Question\Created;
use App\Formatter\Formatter;
use Carbon\Carbon;
use Discuz\Foundation\EventGeneratorTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @package App\Models
 *
 * @property int $id
 * @property int $thread_id
 * @property int $user_id
 * @property int $be_user_id
 * @property string $content
 * @property string $ip
 * @property int $port
 * @property float $price
 * @property float $onlooker_unit_price
 * @property float $onlooker_price
 * @property int $onlooker_number
 * @property bool $is_onlooker
 * @property int $is_answer
 * @property bool $is_anonymous
 * @property int $is_approved
 * @property Carbon created_at
 * @property Carbon updated_at
 * @property Carbon expired_at
 * @property User $user
 * @property User $beUser
 * @property UserWalletLog $userWalletLog
 */
class Question extends Model
{
    use EventGeneratorTrait;

    const EXPIRED_DAY = 7; // 过期时间 (day)

    const TYPE_OF_UNANSWERED = 0; // 未回答

    const TYPE_OF_ANSWERED = 1; // 已回答

    const TYPE_OF_EXPIRED = 2; // 已过期

    protected $fillable = [
        'thread_id',
        'user_id',
        'be_user_id',
        'content',
        'ip',
        'port',
        'price',
        'onlooker_unit_price',
        'onlooker_price',
        'onlooker_number',
        'is_onlooker',
        'is_answer',
        'is_anonymous',
        'is_approved',
        'expired_at',
    ];

    /**
     * {@inheritdoc}
     */
    protected $casts = [
        'is_onlooker' => 'boolean',
        'is_anonymous' => 'boolean',
    ];

    /**
     * {@inheritdoc}
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'expired_at',
    ];

    /**
     * The text formatter instance.
     *
     * @var Formatter
     */
    protected static $formatter;

    /**
     * Unparse the parsed content.
     *
     * @param string $value
     * @return string
     */
    public function getContentAttribute($value)
    {
        return static::$formatter->unparse($value);
    }

    /**
     * Get the parsed/raw content.
     *
     * @return string
     */
    public function getParsedContentAttribute()
    {
        return $this->attributes['content'];
    }

    /**
     * Parse the content before it is saved to the database.
     *
     * @param string $value
     */
    public function setContentAttribute($value)
    {
        $this->attributes['content'] = $value ? static::$formatter->parse($value, $this) : null;
    }

    /**
     * Set the parsed/raw content.
     *
     * @param string $value
     */
    public function setParsedContentAttribute($value)
    {
        $this->attributes['content'] = $value;
    }

    /**
     * Get the content rendered as HTML.
     *
     * @return string
     */
    public function formatContent()
    {
        if (empty($this->attributes['content'])) {
            return $this->attributes['content'];
        }

        return static::$formatter->render($this->attributes['content']);
    }

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

        $self->raise(new Created($self));

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
    public function beUser()
    {
        return $this->belongsTo(User::class, 'be_user_id', 'id');
    }

    public function userWalletLog()
    {
        return $this->belongsTo(UserWalletLog::class, 'id', 'question_id');
    }

    /**
     * Get the text formatter instance.
     *
     * @return Formatter
     */
    public static function getFormatter()
    {
        return static::$formatter;
    }

    /**
     * Set the text formatter instance.
     *
     * @param Formatter $formatter
     */
    public static function setFormatter(Formatter $formatter)
    {
        static::$formatter = $formatter;
    }
}
