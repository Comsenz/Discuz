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

/**
 * @property int $id
 * @property int $dialog_message_id
 * @property int $sender_user_id
 * @property int $recipient_user_id
 * @property Carbon $sender_read_at
 * @property Carbon $recipient_read_at
 * @property Carbon $sender_deleted_at
 * @property Carbon $recipient_deleted_at
 * @property Carbon $updated_at
 * @property Carbon $created_at
 * @property User $sender
 * @property User $recipient
 * @property DialogMessage $dialogMessage
 */
class Dialog extends Model
{
    /**
     * {@inheritdoc}
     */
    protected $table = 'dialog';

    /**
     * {@inheritdoc}
     */
    protected $dates = [
        'sender_read_at',
        'recipient_read_at',
        'updated_at',
        'created_at',
    ];

    /**
     * @var array
     */
    protected $fillable = [];

    /**
     * @param int $sender_user_id
     * @param int $recipient_user_id
     * @return static
     */
    public static function build(int $sender_user_id, int $recipient_user_id)
    {
        $dialog = new static;

        $dialog->sender_user_id = $sender_user_id;
        $dialog->recipient_user_id = $recipient_user_id;

        return $dialog;
    }

    public static function buildOrFetch(int $sender_user_id, int $recipient_user_id)
    {
        $dialog = self::query()
            ->where(['sender_user_id' => $sender_user_id, 'recipient_user_id' => $recipient_user_id])
            ->orWhere(function ($query) use ($recipient_user_id, $sender_user_id) {
                $query->where(['sender_user_id' => $recipient_user_id, 'recipient_user_id' => $sender_user_id]);
            })
            ->first();

        if (!$dialog) {
            $dialog = self::build($sender_user_id, $recipient_user_id);
            $dialog->save();
        }

        return $dialog;
    }

    /**
     * 设置已读
     * @param $type (sender|recipient)
     */
    public function setRead($type)
    {
        $this->forceFill([$type . '_read_at' => Carbon::now()])->save();
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_user_id');
    }

    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_user_id');
    }

    public function dialogMessage()
    {
        return $this->belongsTo(DialogMessage::class);
    }
}
