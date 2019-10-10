<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: StopWord.php xxx 2019-10-09 20:14:00 LiuDongdong $
 */

namespace App\Models;

use App\Events\StopWord\Created;
use Carbon\Carbon;
use Discuz\Foundation\EventGeneratorTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property string $type
 * @property string $class
 * @property string $find
 * @property string $replacement
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @package App\Models
 */
class StopWord extends Model
{
    use EventGeneratorTrait;

    /**
     * Create a new stop word.
     *
     * @param string $type
     * @param string $class
     * @param string $find
     * @param string $replacement
     * @param User $user
     * @return static
     */
    public static function build($type, $class, $find, $replacement, $user)
    {
        $stopWord = new static;

        $stopWord->user_id = $user->id;
        $stopWord->type = $type;
        $stopWord->class = $class;
        $stopWord->find = $find;
        $stopWord->replacement = $replacement;

        $stopWord->raise(new Created($stopWord));

        return $stopWord;
    }

    /**
     * Define the relationship with the discussion's author.
     *
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
