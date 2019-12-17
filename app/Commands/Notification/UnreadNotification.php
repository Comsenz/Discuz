<?php
declare (strict_types = 1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: UnreadNotification.php XXX 2019-11-15 11:20:00 yanchen $
 */

namespace App\Commands\Notification;

use App\Exceptions\NoUserException;
use App\Models\User;
use Discuz\Api\JsonApiResponse;
use Illuminate\Support\Arr;
use Illuminate\Validation\Factory as Validator;
use Illuminate\Notifications\DatabaseNotification;


class UnreadNotification
{
    const TYPE_LIKED = 'App\\Notifications\\Liked';
    const TYPE_REPLIED = 'App\\Notifications\\Replied';
    const TYPE_REWARDED = 'App\\Notifications\\Rewarded';

    private $types = [
        1 => self::TYPE_REPLIED,
        2 => self::TYPE_LIKED,
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

        $user = User::find($this->actor->id);
        if (! $user)
            throw new NoUserException();

        $notifications = DatabaseNotification::selectRaw('type,count(*) as count')
            ->where('read_at', null)
            ->where('notifiable_id', $this->actor->id)
            ->groupBy('type')
            ->pluck('count', 'type')
            ->toArray();

        $data = [
            1 => Arr::get($notifications, $this->types[1], 0),
            2 => Arr::get($notifications, $this->types[2], 0),
            3 => Arr::get($notifications, $this->types[3], 0),
        ];

        return new JsonApiResponse([
            'code' => '0',
            'msg' => 'succ.',
            'data'=> $data
        ]);
    }

}
