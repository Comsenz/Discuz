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
 * @property int $thread_id
 * @property int $post_id
 * @property int $user_id
 * @property int $type
 * @property int $status
 * @property string $reason
 * @property string $file_name
 * @property string $file_id
 * @property int $width
 * @property int $height
 * @property string $media_url
 * @property string $cover_url
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Thread $thread
 * @property Post $post
 */
class ThreadVideo extends Model
{
    const TYPE_OF_VIDEO = 0; // 视频

    const TYPE_OF_AUDIO = 1; // 音频

    const VIDEO_STATUS_TRANSCODING = 0; // 转码中

    const VIDEO_STATUS_SUCCESS = 1;     // 转码完成

    const VIDEO_STATUS_FAIL = 2;        // 转码失败

    /**
     * {@inheritdoc}
     */
    protected $table = 'thread_video';

    /**
     * {@inheritdoc}
     */
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    /**
     * Define the relationship with the video's thread.
     *
     * @return BelongsTo
     */
    public function thread()
    {
        return $this->belongsTo(Thread::class);
    }

    /**
     * Define the relationship with the audio's post.
     *
     * @return BelongsTo
     */
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
