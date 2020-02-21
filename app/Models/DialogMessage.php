<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Models;

use App\Formatter\Formatter;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $user_id
 * @property int $dialog_id
 * @property int $message_text
 * @property Carbon $updated_at
 * @property Carbon $created_at
 * @package App\Models
 */
class DialogMessage extends Model
{

    /**
     * {@inheritdoc}
     */
    protected $table = 'dialog_message';

    /**
     * {@inheritdoc}
     */
    protected $dates = [
        'updated_at',
        'created_at',
    ];

    /**
     * The text formatter instance.
     *
     * @var Formatter
     */
    protected static $formatter;

    /**
     * @var array
     */
    protected $fillable = [];


    public function getMessageTextAttribute($value)
    {
        return static::$formatter->unparse($value);
    }

    public function getParsedMessageTextAttribute()
    {
        return $this->attributes['message_text'];
    }

    public function setMessageTextAttribute($value)
    {
        $this->attributes['message_text'] = $value ? static::$formatter->parse($value, $this) : null;

    }

    public function setParsedMessageTextAttribute($value)
    {
        $this->attributes['message_text'] = $value;
    }

    public function formatMessageText()
    {
        $messageText = $this->attributes['message_text'] ?: '';

        $messageText = $messageText ? static::$formatter->render($messageText) : '';

        return $messageText;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
