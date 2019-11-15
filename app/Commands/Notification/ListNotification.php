<?php
declare (strict_types = 1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: ListNotification.php XXX 2019-11-15 11:20:00 yanchen $
 */

namespace App\Commands\Notification;

use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Validation\Factory as Validator;
use Illuminate\Validation\ValidationException;
use App\Models\Order;


class ListNotification
{
    const TYPE_LIKE = 'App\\Notifications\\Liked';
    const TYPE_REPLIED = 'App\\Notifications\\Replied';
    const TYPE_REWARDED = 'App\\Notifications\\Rewarded';

    private $types = [
        1 => self::TYPE_REPLIED,
        2 => self::TYPE_LIKE,
        3 => self::TYPE_REWARDED,
    ];

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
    public function handle(Validator $validator)
    {
        // 判断有没有权限执行此操作
        // $this->assertCan($this->actor, 'createCircle');
        // 验证参数
        $validator_info = $validator->make($this->data, [
            'type' => 'filled',
            'type'=>'in:1,2,3',
        ]);

        if ($validator_info->fails()) {
            throw new ValidationException($validator_info);
        }

        $user = User::find($this->actor->id);
        $notifications =  $user->notifications()->where('type', Arr::get($this->types , Arr::get($this->data, 'type')))->get();
        $user->unreadNotifications->markAsRead();

       return $notifications;
    }

}
