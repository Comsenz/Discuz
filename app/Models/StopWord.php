<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Models;

use App\Events\StopWord\Created;
use Carbon\Carbon;
use Discuz\Database\ScopeVisibilityTrait;
use Discuz\Foundation\EventGeneratorTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property string $ugc
 * @property string $username
 * @property string $find
 * @property string $replacement
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @package App\Models
 */
class StopWord extends Model
{
    use EventGeneratorTrait;
    use ScopeVisibilityTrait;

    /**
     * @var array
     */
    protected $fillable = ['user_id', 'ugc', 'username', 'find', 'replacement'];

    /**
     * Create a new stop word.
     *
     * @param string $ugc
     * @param string $username
     * @param string $find
     * @param string $replacement
     * @param User $user
     * @return static
     */
    public static function build($ugc, $username, $find, $replacement, $user)
    {
        $stopWord = new static;

        $stopWord->user_id = $user->id;
        $stopWord->ugc = $ugc;
        $stopWord->username = $username;
        $stopWord->find = $find;
        $stopWord->replacement = $replacement;

        $stopWord->raise(new Created($stopWord, $user));

        return $stopWord;
    }

    /**
     * Define the relationship with the stop word's author.
     *
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
