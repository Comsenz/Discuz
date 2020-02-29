<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $post_id
 * @property string $stop_word
 * @package App\Models
 */
class PostMod extends Model
{
    /**
     * {@inheritdoc}
     */
    protected $table = 'post_mod';

    /**
     * {@inheritdoc}
     */
    protected $primaryKey = 'post_id';

    /**
     * {@inheritdoc}
     */
    protected $fillable = ['post_id', 'stop_word'];

    /**
     * {@inheritdoc}
     */
    public $incrementing = false;

    /**
     * {@inheritdoc}
     */
    public $timestamps = false;

    /**
     * Define the relationship with the post's mod stop words.
     *
     * @return BelongsTo
     */
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
