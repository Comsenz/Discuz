<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $dialog_message_id
 * @property int $sender_user_id
 * @property int $recipient_user_id
 * @property Carbon $updated_at
 * @property Carbon $created_at
 * @package App\Models
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
        $dialog =  self::query()
            ->where(['sender_user_id'=>$sender_user_id,'recipient_user_id'=>$recipient_user_id])
            ->orWhere(function ($query) use ($recipient_user_id,$sender_user_id) {
                $query->where(['sender_user_id'=>$recipient_user_id,'recipient_user_id'=>$sender_user_id]);
            })
            ->first();

        if (!$dialog) {
            $dialog = self::build($sender_user_id, $recipient_user_id);
            $dialog->save();
        }

        return $dialog;
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
        return $this->hasOne(DialogMessage::class);
    }
}
