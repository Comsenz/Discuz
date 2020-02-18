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
     * @var array
     */
    protected $fillable = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
