<?php
declare (strict_types = 1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: ListOrder.php XXX 2019-10-24 11:20:00 zhouzhou $
 */

namespace App\Commands\Order;

use Illuminate\Support\Collection;
use Illuminate\Support\Arr;
use App\Models\Order;

class ListOrder
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
     * @return order
     */
    public function handle()
    {
        // 判断有没有权限执行此操作
        // $this->assertCan($this->actor, 'createCircle');
        $limit = (int)Arr::get($this->data, 'size', 10);
        $page = (int)Arr::get($this->data, 'page', 1);
        if ($page < 1) {
            $page = 1;
        }
        $offset = $limit * ($page - 1);
     	return Order::where('user_id', $this->actor->id)->orderBy('id', 'ASC')->offset($offset)->limit($limit)->get();
    }

}
