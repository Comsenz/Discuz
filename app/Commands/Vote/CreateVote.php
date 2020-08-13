<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Commands\Vote;

use App\Censor\Censor;
use App\Models\User;
use App\Models\Vote;
use App\Repositories\UserRepository;
use App\Validators\VoteValidator;
use Carbon\Carbon;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Foundation\EventsDispatchTrait;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Bus\Dispatcher as DispatcherBus;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Support\Arr;

class CreateVote
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

    public function handle(UserRepository $user, Dispatcher $events, Censor $censor, DispatcherBus $bus, VoteValidator  $validation)
    {
        $this->events = $events;

        $this->assertCan($this->actor, 'vote.create');

        $validation->valid($this->attributes);

        $data = [
            'name' => Arr::get($this->attributes, 'name'),
            'type' => Arr::get($this->attributes, 'type'),
            'user_id' => Arr::get($this->attributes, 'user_id', $this->actor->id),
            'start_at' => Arr::get($this->attributes, 'start_at', Carbon::now()->toDate()),
            'end_at' => Arr::get($this->attributes, 'end_at'),
            'thread_id' => Arr::get($this->attributes, 'thread_id', 0),
            ];
        $vote = Vote::build($data);
        $vote->save();

        if ($vote) {
            foreach (Arr::get($this->attributes, 'contents') as $content) {
                $bus->dispatchNow(
                    new CreateVoteOptions($this->actor, $vote->id, $content)
                );
            }
        }
        return $vote;

    }
}
