<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Commands\Dialog;

use App\Models\Dialog;
use App\Models\User;
use App\Repositories\UserRepository;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Foundation\EventsDispatchTrait;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Arr;

class BatchCreateDialog
{
    use AssertPermissionTrait;
    use EventsDispatchTrait;

    /**
     * @var
     */
    public $attributes;

    /**
     * The user performing the action.
     *
     * @var User
     */
    public $actor;

    /**
     * @param User $actor
     * @param $attributes
     */
    public function __construct(User $actor, $attributes)
    {
        $this->actor = $actor;
        $this->attributes = $attributes;
    }

    public function handle(Dialog $dialog, UserRepository $user, Dispatcher $events)
    {
        $this->events = $events;

        $result = ['data' => [], 'meta' => []];

        if (! $this->actor->can('createDialog')) {
            $result['meta'][] = ['message' => 'permission_denied'];
            return $result;
        }

        $sender = $this->actor->id;
        $recipients = explode(',', Arr::get($this->attributes, 'recipient_username'));

        foreach ($recipients as $recipient) {
            $recipientUser = $user->query()->where('username', $recipient)->first();

            //处理错误的用户名
            if (!$recipientUser) {
                $result['meta'][] = ['name' => $recipient, 'message' => ' not found '];
                continue;
            }

            $result['data'][] = $dialog::buildOrFetch($sender, $recipientUser->id);

        }
        return $result;
    }
}
