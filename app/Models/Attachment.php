<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Models;

use App\Events\Attachment\Created;
use Discuz\Foundation\EventGeneratorTrait;
use Discuz\Database\ScopeVisibilityTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

/**
 * @property string $uuid
 * @property int $user_id
 * @property int $post_id
 * @property int $order
 * @property int $is_gallery
 * @property int $is_sound
 * @property int $type
 * @property int $is_approved
 * @property int $is_remote
 * @property string $attachment
 * @property string $file_path
 * @property string $file_name
 * @property int $file_size
 * @property string $file_type
 * @property string $ip
 * @property User $user
 * @property Post $post
 */
class Attachment extends Model
{
    use EventGeneratorTrait;
    use ScopeVisibilityTrait;

    const FIX_WIDTH = 500;

    const TYPE_OF_FILE = 0;

    const TYPE_OF_IMAGE = 1;

    const TYPE_OF_AUDIO = 2;

    const TYPE_OF_VIDEO = 3;

    const UNAPPROVED = 0;

    const APPROVED = 1;

    /**
     * {@inheritdoc}
     */
    protected $casts = [
        'type' => 'integer',
        'is_approved' => 'integer',
        'is_gallery' => 'boolean',
        'is_remote' => 'boolean',
    ];

    /**
     * type：0 附件 1 图片 2 音频 3 视频
     *
     * @var array
     */
    public static $allowTypes = [
        'file',
        'img',
        'audio',
        'video',
    ];

    /**
     * @param int $userId 用户id
     * @param int $type 类型 type：0 附件 1 图片 2 音频 3 视频
     * @param string $name 文件名称
     * @param string $path 文件路径
     * @param string $originalName 文件原名
     * @param int $size 文件大小
     * @param string $mime 文件 mime 类型
     * @param bool $isRemote 是否云存储
     * @param bool $isApproved 是否合法
     * @param string $ip ip 地址
     * @param int $order 文件顺序
     * @return static
     */
    public static function build(
        $userId,
        $type,
        $name,
        $path,
        $originalName,
        $size,
        $mime,
        $isRemote,
        $isApproved,
        $ip,
        $order = 0
    ) {
        $attachment = new static;

        $attachment->uuid = Str::uuid();
        $attachment->user_id = $userId;
        $attachment->order = $order;

        // TODO: remove is_gallery & rename is_sound to type
        // $attachment->type = $type;
        $attachment->is_gallery = $type === 1;
        $attachment->is_sound = $type;

        $attachment->is_remote = $isRemote;
        $attachment->is_approved = $isApproved;
        $attachment->attachment = $name;
        $attachment->file_path = $path;
        $attachment->file_name = $originalName;
        $attachment->file_size = $size;
        $attachment->file_type = $mime;
        $attachment->ip = $ip;

        $attachment->raise(new Created($attachment));

        return $attachment;
    }

    /**
     * 检测是否存在有不合法的附件
     *
     * @param $ids
     * @return bool
     */
    public static function approvedInExists($ids)
    {
        $self = new static;

        return $self->whereIn('id', $ids)->pluck('is_approved')->contains(0);
    }

    /**
     * 获取替换缩略图名称
     *
     * @param $imgPath
     * @return string
     */
    public static function replaceThumb($imgPath)
    {
        return Str::replaceLast('.', '_thumb.', $imgPath);
    }

    /**
     * Define the relationship with the attachment's author.
     *
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Define the relationship with the attachment's post.
     *
     * @return BelongsTo
     */
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
