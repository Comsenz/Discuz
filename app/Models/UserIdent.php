<?php
declare(strict_types=1);


namespace App\Models;

use App\Events\UserIdent\Created;
use Discuz\Foundation\EventGeneratorTrait;
use Discuz\Database\ScopeVisibilityTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * @param $type        请求方式
 * @param $code        验证码参数
 * @param $ipAddress   创建的IP地址
 */
class UserIdent extends Model
{
    use EventGeneratorTrait;
    use ScopeVisibilityTrait;

    /**
     * 与模型关联的数据表.
     *
     * @var string
     */
    protected $table = 'user_ident';

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
    protected $dateFormat = 'U';

    /**
     * 存储时间戳的字段名
     *
     * @var string
     */
    const CREATED_AT = 'createtime';

    /**
     * 存储时间戳的字段名
     *
     * @var string
     */
    const UPDATED_AT = 'updatetime';

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
     * 创建站点.
     *
     * @param $type        请求方式
     * @param $code        验证码参数
     * @param $mobile        手机号
     * @return static
     */
    public static function creation(
        $type,
        $code,
        $mobile
    ) {
        // 实例一个站点模型
        $userIdent = new static;

        // 设置模型属性值
        $userIdent->type = $type;
        $userIdent->code = $code;
        $userIdent->mobile = $mobile;
        // 暂存需要执行的事件
        $userIdent->raise(new Created($userIdent));

        // 返回站点模型
        return $userIdent;
    }

}