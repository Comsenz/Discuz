<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $vote_id
 * @property string $content
 * @property int $count
 * @property Carbon $updated_at
 * @property Carbon $created_at
 * @package App\Models
 */
class VoteOption extends Model
{

    /**
     * {@inheritdoc}
     */
    protected $dates = [
        'updated_at',
        'created_at',
    ];

    /**
     * @var string[]
     */
    protected $fillable = [];

    public static function build(array $attributes)
    {
        $voteOption = new static;
        $voteOption->attributes = $attributes;
        $voteOption->count = 0;

        return $voteOption;
    }

    public function vote()
    {
        return $this->belongsTo(Vote::class);
    }

}
