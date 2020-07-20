<?php


namespace App\Commands\Vote;


use App\Models\User;
use App\Repositories\VoteRepository;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Foundation\EventsDispatchTrait;
use Illuminate\Contracts\Events\Dispatcher;

class DeleteVote
{
    use AssertPermissionTrait;
    use EventsDispatchTrait;

    /**
     * The user performing the action.
     *
     * @var User
     */
    protected $actor;

    protected $id;


    public function __construct($id, User $actor)
    {
        $this->id = $id;
        $this->actor = $actor;
    }

    public function handle(Dispatcher $events, VoteRepository $votes)
    {
        $this->events = $events;

        $this->assertCan($this->actor, 'vote.delete');

        $vote = $votes->findOrFail($this->id);

        $vote->delete();

        return $vote;
    }

}
