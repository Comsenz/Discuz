<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Commands\Vote;

use App\Censor\Censor;
use App\Models\User;
use App\Models\Vote;
use App\Models\VoteOption;
use App\Repositories\UserRepository;
use App\Validators\VoteValidator;
use Carbon\Carbon;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Foundation\EventsDispatchTrait;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Bus\Dispatcher as DispatcherBus;
use Illuminate\Support\Arr;

class CreateVoteOptions
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

        $data = [];
        $vote_id = Arr::get($this->attributes, 'vote_id');
        foreach (Arr::get($this->attributes, 'content') as $item) {
            array_push($data, ['vote_id' => $vote_id, 'content' => $item]);
        }

        return VoteOption::query()->insert($data);

    }
}
