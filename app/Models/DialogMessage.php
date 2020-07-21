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

use App\Formatter\DialogMessageFormatter;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property int $user_id
 * @property int $attachment_id
 * @property int $dialog_id
 * @property int $message_text
 * @property Carbon $updated_at
 * @property Carbon $created_at
 * @package App\Models
 */
class DialogMessage extends Model
{
    const SUMMARY_LENGTH = 40;

    const SUMMARY_END_WITH = '...';

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

    public function getSummaryAttribute()
    {
        $message_text = Str::of($this->message_text ?: '');

        if ($message_text->length() > self::SUMMARY_LENGTH) {
            $message_text = static::$formatter->parse(
                $message_text->substr(0, self::SUMMARY_LENGTH)->finish(self::SUMMARY_END_WITH)
            );
            $message_text = static::$formatter->render($message_text);
        } else {
            $message_text = $this->formatMessageText();
        }

        return str_replace('<br>', '', $message_text);
    }

    public function formatMessageText()
    {
        $messageText = $this->attributes['message_text'] ?: '';

        $messageText = $messageText ? static::$formatter->render($messageText) : '';

        return $messageText;
    }

    public static function build($user_id, $dialog_id, $attachment_id, $message_text)
    {
        $dialogMessage = new static();

        $dialogMessage->user_id       = $user_id;
        $dialogMessage->dialog_id     = $dialog_id;
        $dialogMessage->attachment_id = $attachment_id;
        $dialogMessage->message_text  = $message_text;

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

    public function attachment()
    {
        return $this->belongsTo(Attachment::class)->where('type', Attachment::TYPE_OF_DIALOG_MESSAGE);
    }
}
