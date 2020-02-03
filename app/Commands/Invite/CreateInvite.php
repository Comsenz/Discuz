<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Commands\Invite;

use App\Events\Invite\Saving;
use App\Models\Invite;
use App\Models\User;
use Carbon\Carbon;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Auth\Exception\PermissionDeniedException;
use Discuz\Foundation\EventsDispatchTrait;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class CreateInvite
{
    use AssertPermissionTrait;
    use EventsDispatchTrait;

    /**
     * The user performing the action.
     *
     * @var User
     */
    public $actor;

    /**
     * The attributes of the new invitation.
     *
     * @var array
     */
    public $data;

    /**
     * @param User $actor
     * @param array $data
     */
    public function __construct(User $actor, array $data)
    {
        $this->actor = $actor;
        $this->data = $data;
    }

    /**
     * @param Dispatcher $events
     * @return Invite
     * @throws PermissionDeniedException
     */
    public function handle(Dispatcher $events)
    {
        $this->events = $events;

        $this->assertCan($this->actor, 'createInvite');

        $invite = Invite::creation(
            Arr::get($this->data, 'attributes.group_id'),
            2,
            Str::random(32),
            Carbon::now()->timestamp,
            Carbon::now()->addDays(7)->timestamp,
            $this->actor->id
        );

        $this->events->dispatch(
            new Saving($invite, $this->actor, $this->data)
        );

        $invite->save();

        $this->dispatchEventsFor($invite);

        return $invite;
    }
}
