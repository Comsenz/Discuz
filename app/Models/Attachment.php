<?php

/**
 * Copyright (C) 2020 Tencent Cloud.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace App\Models;

use App\Events\Attachment\Created;
use Carbon\Carbon;
use Discuz\Database\ScopeVisibilityTrait;
use Discuz\Foundation\EventGeneratorTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Illuminate\Contracts\Cache\Repository as Cache;

/**
 * @property int $id
 * @property string $uuid
 * @property int $user_id
 * @property int $type_id
 * @property int $order
 * @property int $type
 * @property int $is_remote
 * @property int $is_approved
 * @property string $attachment
 * @property string $file_path
 * @property string $full_path
 * @property string $thumb_path
 * @property string $blur_path
 * @property string $file_name
 * @property int $file_size
 * @property string $file_type
 * @property string $ip
 * @property Carbon $created_at
 * @property Carbon $updated_at
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

    const TYPE_OF_DIALOG_MESSAGE = 4;

    const TYPE_OF_ANSWER = 5; // 回答图片

    const UNAPPROVED = 0;

    const APPROVED = 1;

    /**
     * {@inheritdoc}
     */
    protected $casts = [
        'type' => 'integer',
        'is_approved' => 'integer',
        'is_remote' => 'boolean',
    ];

    /**
     * type：0 帖子附件 1 帖子图片 2 帖子音频 3 帖子视频 4 消息图片 5 回答图片
     *
     * @var array
     */
    public static $allowTypes = [
        self::TYPE_OF_FILE => 'file',
        self::TYPE_OF_IMAGE => 'img',
        self::TYPE_OF_AUDIO => 'audio',
        self::TYPE_OF_VIDEO => 'video',
        self::TYPE_OF_DIALOG_MESSAGE => 'dialogMessage',
        self::TYPE_OF_ANSWER => 'answer',
    ];

    /**
     * @param int $userId 用户id
     * @param int $type 附件类型(0帖子附件，1帖子图片，2帖子视频，3帖子音频，4消息图片)
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
        $attachment->type = $type;
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

    public static function getFileToken($actor)
    {
        /** @var Cache $cache */
        $cache = app(Cache::class);
        $token = $cache->get('attachments_user_' . $actor->id);
        if (!$token) {
            $token = SessionToken::query()
                ->where('scope', 'attachment')
                ->where('user_id', $actor->id)
                ->first();
            if (!$token) {
                $token = SessionToken::generate('attachment', null, $actor->id, 3600);
                $token->save();
            }
            $token = $token->token;
            $cache->put('attachments_user_' . $actor->id, $token, 3599);
        }
        return $token;
    }

    /**
     * @param string $value
     * @return string
     */
    public function getFilePathAttribute($value)
    {
        return Str::finish($value, '/');
    }

    /**
     * @return string
     */
    public function getFullPathAttribute()
    {
        return $this->file_path . $this->attachment;
    }

    /**
     * @return string
     */
    public function getThumbPathAttribute()
    {
        return $this->file_path . Str::replaceLast('.', '_thumb.', $this->attachment);
    }

    /**
     * @return string
     */
    public function getBlurPathAttribute()
    {
        $parts = explode('.', $this->attachment);

        $parts[0] = md5($parts[0]);

        return $this->file_path . implode('_blur.', $parts);
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
        return $this->belongsTo(Post::class, 'type_id');
    }

}
