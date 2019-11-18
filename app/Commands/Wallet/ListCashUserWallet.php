<?php
declare (strict_types = 1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: ListCashUserWallet.php XXX 2019-11-10 15:00 zhouzhou $
 */

namespace App\Commands\Wallet;

use Illuminate\Validation\Factory as Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Arr;
use App\Models\UserWalletCash;

class ListCashUserWallet
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
    public function __construct($actor, $data)
    {
        $this->actor = $actor;
        $this->data  = $data;
    }

    /**
     * 执行命令
     * @return model UserWallet
     * @throws Exception
     */
    public function handle(Validator $validator)
    {
        // 判断有没有权限执行此操作
        // $this->assertCan($this->actor, 'listCashUserWallet');
        // 验证参数
        $validator_info = $validator->make($this->data, [
            'size' => 'sometimes|required|integer|min:1',
        ]);

        if ($validator_info->fails()) {
            throw new ValidationException($validator_info);
        }
        $limit = (int)Arr::get($this->data, 'size', 10);
        $page = (int)Arr::get($this->data, 'page', 1);
        if ($page < 1) {
            $page = 1;
        }
        $offset = $limit * ($page - 1);

        return UserWalletCash::where('user_id', $this->actor->id)->orderBy('id', 'DESC')->offset($offset)->limit($limit)->get();
    }
}
