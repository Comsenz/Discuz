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
use App\Repositories\VoteOptionRepository;
use App\Validators\VoteValidator;
use Carbon\Carbon;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Foundation\EventsDispatchTrait;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Bus\Dispatcher as DispatcherBus;
use Illuminate\Support\Arr;

class EditVoteOptions
{
    use AssertPermissionTrait;
    use EventsDispatchTrait;

    public $vote_id;

    public $option_id;

    public $content;

    /**
     * The user performing the action.
     *
     * @var User
     */
    public $actor;

    /**
     * @param User $actor
     * @param int $vote_id
     * @param int $option_id
     * @param string $content
     */
    public function __construct(User $actor, $vote_id, $option_id, $content)
    {
        $this->actor = $actor;
        $this->vote_id = $vote_id;
        $this->option_id = $option_id;
        $this->content = $content;
    }

    /**
     * @param Dispatcher $events
     * @param VoteOptionRepository $voteOptions
     * @return VoteOption
     */
    public function handle(Dispatcher $events, VoteOptionRepository $voteOptions)
    {
        $this->events = $events;
        /** @var VoteOption $voteOption */
        $voteOption = $voteOptions->findOrFail($this->vote_id, $this->option_id);
        $voteOption->content = $this->content;
        $voteOption->save();

        return $voteOption;

    }
}
