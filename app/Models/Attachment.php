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
 * @property int $is_gallery
 * @property int $is_approved
 * @property string $attachment
 * @property string $file_path
 * @property string $file_name
 * @property int $file_size
 * @property string $file_type
 * @property int $is_remote
 * @property string $ip
 * @property User $user
 * @property Post $post
 */
class Attachment extends Model
{
    use EventGeneratorTrait;
    use ScopeVisibilityTrait;

    /**
     * {@inheritdoc}
     */
    protected $casts = [
        'is_gallery' => 'boolean',
        'is_remote' => 'boolean',
    ];

    /**
     * 创建附件.
     *
     * @param int $user_id 用户id
     * @param int $post_id 回复id
     * @param int $isGallery 是否是帖子图片
     * @param int $isApproved 是否合法（敏感图）
     * @param int $is_remote 是否远程附件
     * @param string $attachment 系统生成的名称
     * @param string $file_path 文件路径
     * @param string $file_name 文件原名称
     * @param int $file_size 文件大小
     * @param string $file_type 文件类型
     * @param string $ip 创建的IP地址
     * @return static
     */
    public static function creation(
        $user_id,
        $post_id,
        $isGallery,
        $isApproved,
        $attachment,
        $file_path,
        $file_name,
        $file_size,
        $file_type,
        $is_remote,
        $ip
    ) {
        // 实例一个附件模型
        $attach = new static;

        // 设置模型属性值
        $attach->uuid = Str::uuid();
        $attach->user_id = $user_id;
        $attach->post_id = $post_id;
        $attach->is_gallery = $isGallery;
        $attach->is_approved = $isApproved;
        $attach->attachment = $attachment;
        $attach->file_path = $file_path;
        $attach->file_name = $file_name;
        $attach->file_size = $file_size;
        $attach->file_type = $file_type;
        $attach->is_remote = $is_remote;
        $attach->ip = $ip;

        // 暂存需要执行的事件
        $attach->raise(new Created($attach));

        // 返回附件模型
        return $attach;
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
