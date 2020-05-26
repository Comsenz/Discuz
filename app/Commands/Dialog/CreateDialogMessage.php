<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Commands\Dialog;

use App\Censor\Censor;
use App\Models\Dialog;
use App\Models\DialogMessage;
use App\Models\User;
use App\Repositories\DialogRepository;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Auth\Exception\PermissionDeniedException;
use Discuz\Foundation\EventsDispatchTrait;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Arr;

class CreateDialogMessage
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

    public function handle(DialogRepository $dialog, Dispatcher $events, Censor $censor)
    {
        $this->events = $events;

        $this->assertCan($this->actor, 'dialog.create');

        $dialog_id = Arr::get($this->attributes, 'dialog_id');

        //敏感词检查
        $message_text = trim($censor->checkText(Arr::get($this->attributes, 'message_text')));

        /** @var Dialog $dialogRes */
        $dialogRes = $dialog->findOrFail($dialog_id, $this->actor);

        //在黑名单中，不能发消息
        if ($dialogRes->sender_user_id == $this->actor->id) {
            $user = $dialogRes->recipient;
        } else {
            $user = $dialogRes->sender;
        }
        if (!$user) {
            throw new ModelNotFoundException();
        }
        if (in_array($this->actor->id, array_column($user->deny->toArray(), 'id'))) {
            throw new PermissionDeniedException('user_deny');
        }

        $dialogMessage = DialogMessage::build($this->actor->id, $dialog_id, $message_text);
        $dialogMessageRes = $dialogMessage->save();

        if ($dialogMessageRes) {
            //发送新消息后设置对方未读
            if ($dialogRes->sender_user_id == $this->actor->id) {
                $dialogRes->recipient_read_at = null;
            } else {
                $dialogRes->sender_read_at = null;
            }
            $dialogRes->dialog_message_id = $dialogMessage->id;

            $dialogRes->save();
        }

        return $dialogMessage;
    }
}
