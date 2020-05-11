<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Models;

use App\Formatter\DialogMessageFormatter;
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
     * @var DialogMessageFormatter
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

    public static function build($user_id, $dialog_id, $message_text)
    {
        $dialogMessage = new static();

        $dialogMessage->user_id      = $user_id;
        $dialogMessage->dialog_id    = $dialog_id;
        $dialogMessage->message_text = $message_text;

        return $dialogMessage;
    }

    public static function setFormatter(DialogMessageFormatter $formatter)
    {
        static::$formatter = $formatter;
    }

    public static function getFormatter()
    {
        return static::$formatter;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
