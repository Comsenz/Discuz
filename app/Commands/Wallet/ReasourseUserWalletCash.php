<?php
declare (strict_types = 1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: ReasourseUserWalletCash.php XXX 2019-11-10 10:00 zhouzhou $
 */

namespace App\Commands\Wallet;

use App\Models\UserWalletCash;

class ReasourseUserWalletCash
{
    /**
     * 执行操作的用户.
     *
     * @var User
     */
    public $actor;

    /**
     * 初始化命令参数
     * @param User   $actor        执行操作的用户.
     * @param array  $data         请求的数据.
     */
    public function __construct($actor)
    {
        $this->actor   = $actor;
    }

    /**
     * 执行命令
     * @return model UserWalletCash
     * @throws Exception
     */
    public function handle()
    {
        // 判断有没有权限执行此操作
        // $this->assertCan($this->actor, 'UserWalletCash');
        return UserWalletCash::where('user_id', $this->actor->id)->get();
    }

}
