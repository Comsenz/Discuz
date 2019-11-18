<?php
declare (strict_types = 1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: ResourceUserWallet.php XXX 2019-10-23 10:00 zhouzhou $
 */

namespace App\Commands\Wallet;

use App\Models\User;
use App\Models\UserWallet;

class ResourceUserWallet
{
    /**
     * 用户ID
     * @var int
     */
    public $user_id;
    /**
     * 执行操作的用户.
     *
     * @var User
     */
    public $actor;

    /**
     * 初始化命令参数
     * @param int    $user_id       钱包所属用户id.
     * @param User   $actor        执行操作的用户.
     * @param array  $data         请求的数据.
     */
    public function __construct($user_id, User $actor)
    {
        $this->user_id = $user_id;
        $this->actor   = $actor;
    }

    /**
     * 执行命令
     * @return model UserWallet
     * @throws Exception
     */
    public function handle()
    {
        // 判断有没有权限执行此操作
        //$this->assertAdmin($this->actor);
        return UserWallet::find($this->user_id);
    }

}
