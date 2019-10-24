<?php
declare(strict_types=1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: Attach.php30 2019-09-29 16:49 chenkeke $
 */

namespace App\Models;

use App\Events\Attachment\Created;
use Discuz\Foundation\EventGeneratorTrait;
use Discuz\Database\ScopeVisibilityTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * @property 用户id user_id
 * @property 回复id post_id
 * @property 系统生成的名称 attachment
 * @property 文件路径 file_path
 * @property 文件原名称 file_name
 * @property 文件大小 file_size
 * @property 文件类型 file_type
 * @property 是否远程附件 remote
 * @property 创建的IP地址 ip
 */
class Attachment extends Model
{
    use EventGeneratorTrait;
    use ScopeVisibilityTrait;

    /**
     * 与模型关联的数据表.
     *
     * @var string
     */
    protected $table = 'attachments';

    /**
     * 该模型是否被自动维护时间戳.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * 模型的日期字段的存储格式
     *
     * @var string
     */
    protected $dateFormat = 'Y-m-d H:i:s';

    /**
     * 存储时间戳的字段名
     *
     * @var string
     */
    const CREATED_AT = 'created_at';

    /**
     * 存储时间戳的字段名
     *
     * @var string
     */
    const UPDATED_AT = 'updated_at';

    /**
     * 模型的「启动」方法.
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();
    }

    /**
     * 创建附件.
     *
     * @param $user_id    用户id
     * @param $post_id    回复id
     * @param $attachment 系统生成的名称
     * @param $file_path  文件路径
     * @param $file_name  文件原名称
     * @param $file_size  文件大小
     * @param $file_type  文件类型
     * @param $remote     是否远程附件
     * @param $ip         创建的IP地址
     * @return static
     */
    public static function creation(
        $user_id,
        $post_id,
        $attachment,
        $file_path,
        $file_name,
        $file_size,
        $file_type,
        $remote,
        $ip
    ) {
        // 实例一个附件模型
        $attach = new static;

        // 设置模型属性值
        $attach->user_id = $user_id;
        $attach->post_id = $post_id;
        $attach->attachment = $attachment;
        $attach->file_path = $file_path;
        $attach->file_name = $file_name;
        $attach->file_size = $file_size;
        $attach->file_type = $file_type;
        $attach->remote = $remote;
        $attach->ip = $ip;

        // 暂存需要执行的事件
        $attach->raise(new Created($attach));

        // 返回附件模型
        return $attach;
    }

}