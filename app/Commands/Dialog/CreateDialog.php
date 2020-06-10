<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Commands\Dialog;

use App\Censor\Censor;
use App\Models\Dialog;
use App\Models\User;
use App\Repositories\UserRepository;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Auth\Exception\PermissionDeniedException;
use Discuz\Foundation\EventsDispatchTrait;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Bus\Dispatcher as DispatcherBus;
use Illuminate\Support\Arr;

class CreateDialog
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

    public function handle(Dialog $dialog, UserRepository $user, Dispatcher $events, Censor $censor, DispatcherBus $bus)
    {
        $this->events = $events;

        $this->assertCan($this->actor, 'dialog.create');

        $sender = $this->actor->id;
        $recipient = Arr::get($this->attributes, 'recipient_username');

        $recipientUser = $user->query()->where('username', $recipient)->firstOrFail();

        if ($sender == $recipientUser->id) {
            throw new PermissionDeniedException();
        }
        //在黑名单中，不能创建会话
        if (in_array($sender, array_column($recipientUser->deny->toArray(), 'id'))) {
            throw new PermissionDeniedException('user_deny');
        }

        $dialogRes = $dialog::buildOrFetch($sender, $recipientUser->id);

        //创建会话时如传入消息内容，则创建消息
        $message_text = Arr::get($this->attributes, 'message_text', null);
        if ($message_text) {
            $this->attributes['dialog_id'] = $dialogRes->id;
            $bus->dispatchNow(
                new CreateDialogMessage($this->actor, $this->attributes)
            );
        }
        return $dialogRes;
    }
}
