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
use Illuminate\Database\Eloquent\ModelNotFoundException;
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

        $this->assertCan($this->actor, 'create', $dialog);

        $sender = $this->actor->id;
        $recipients = explode(',', Arr::get($this->attributes, 'recipient_username'));
        if (!$recipients) {
            throw new ModelNotFoundException();
        }

        $recipientUnKnowUser = [];
        $dialogRes = [];
        foreach ($recipients as $recipient) {
            $recipientUser = $user->query()->where('username', $recipient)->firstOrFail();

            //处理错误的用户名
            if (!$recipientUser) {
                Arr::prepend($recipientUnKnowUser, $recipient);
                continue;
            }
            $dialogCreate = $dialog::build($sender, $recipientUser->id);
            //dialog_message_id 创建时为默认值0
            $dialogCreate->save();
            Arr::prepend($dialogRes, $dialogCreate);
        }
        return $dialogRes;
    }
}
