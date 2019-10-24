<?php
declare (strict_types = 1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: CreateUserWallet.php XXX 2019-10-24 17:50 zhouzhou $
 */

namespace App\Commands\Wallet;

use App\Exceptions\ErrorException;
use App\Models\UserWallet;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class CreateUserWallet
{
    /**
     * 执行操作的用户.
     *
     * @var User
     */
    public $actor;

    /**
     * 请求的数据.
     *
     * @var array
     */
    public $data;

    /**
     * 初始化命令参数
     * @param User   $actor        执行操作的用户.
     * @param array  $data         请求的数据.
     */
    public function __construct($actor, Collection $data)
    {
        $this->actor = $actor;
        $this->data  = $data;
    }

    /**
     * 执行命令
     * @return model UserWallet
     * @throws Exception
     */
    public function handle()
    {
        // 判断有没有权限执行此操作
        // $this->assertCan($this->actor, 'userWallet');
        $user_wallet = UserWallet::where('user_id', $this->actor->id)->first();
        if (empty($user_wallet)) {
        	return UserWallet::createUserWallet($this->actor->id);
        } else {
        	return $user_wallet;
        }
    	
    }

}
