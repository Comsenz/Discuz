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

}
