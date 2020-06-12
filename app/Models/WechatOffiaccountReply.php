<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Models;

use Carbon\Carbon;
use Discuz\Database\ScopeVisibilityTrait;
use Discuz\Foundation\EventGeneratorTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * @package App\Models
 *
 * @property string $name
 * @property string $key_words
 * @property string $media_id
 * @property int $match_type
 * @property int $reply_type
 * @property int $media_type
 * @property int $type
 * @property int $status
 * @property Carbon created_at
 * @property Carbon updated_at
 * @property Carbon deleted_at
 * @method static create(array $array)
 * @method static where(...$params)
 */
class WechatOffiaccountReply extends Model
{
    use EventGeneratorTrait;
    use ScopeVisibilityTrait;

    protected $fillable = [
        'name',
        'key_words',
        'match_type',
        'reply_type',
        'media_id',
        'media_type',
        'type',
    ];

    /**
     * Create a new OffiaccountReply
     *
     * @param array $attributes
     * @return static
     */
    public static function build(array $attributes)
    {
        $offiaccountReply = new static;

        $offiaccountReply->fill($attributes);

        return $offiaccountReply;
    }

}
