<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Commands\Notification;

use App\Models\User;
use Discuz\Api\JsonApiResponse;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Auth\Exception\NotAuthenticatedException;
use Illuminate\Support\Arr;
use Illuminate\Notifications\DatabaseNotification;

class UnreadNotification
{
    use AssertPermissionTrait;

    /**
     * 点赞通知
     */
    const TYPE_LIKED = 'App\\Notifications\\Liked';

    /**
     * 回复通知
     */
    const TYPE_REPLIED = 'App\\Notifications\\Replied';

    /**
     * 打赏通知
     */
    const TYPE_REWARDED = 'App\\Notifications\\Rewarded';

    private $types = [
        1 => self::TYPE_REPLIED,
        2 => self::TYPE_LIKED,
        3 => self::TYPE_REWARDED,
    ];

    /**
     * The user performing the action.
     *
     * @var User
     */
    public $actor;

    /**
     * @param User $actor
     */
    public function __construct(User $actor)
    {
        $this->actor = $actor;
    }

    /**
     * @return JsonApiResponse
     * @throws NotAuthenticatedException
     */
    public function handle()
    {
        $this->assertRegistered($this->actor);

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
