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
use App\Repositories\VoteRepository;
use App\Validators\VoteValidator;
use Carbon\Carbon;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Auth\Exception\PermissionDeniedException;
use Discuz\Foundation\EventsDispatchTrait;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Bus\Dispatcher as DispatcherBus;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;

class EditVote
{
    use AssertPermissionTrait;
    use EventsDispatchTrait;

    public $attributes;

    public $id;

    /**
     * The user performing the action.
     *
     * @var User
     */
    public $actor;

    /**
     * @param User $actor
     * @param $id
     * @param $attributes
     */
    public function __construct(User $actor, $id, $attributes)
    {
        $this->actor = $actor;
        $this->id = $id;
        $this->attributes = $attributes;
    }

    /**
     * @param UserRepository $user
     * @param Dispatcher $events
     * @param Censor $censor
     * @param DispatcherBus $bus
     * @param VoteValidator $validation
     * @param VoteRepository $votes
     * @return Vote
     * @throws PermissionDeniedException
     * @throws ValidationException
     */
    public function handle(UserRepository $user, Dispatcher $events, Censor $censor, DispatcherBus $bus, VoteValidator  $validation, VoteRepository $votes)
    {
        $this->events = $events;

        $this->assertCan($this->actor, 'vote.create');

        $validation->valid($this->attributes);

        /** @var Vote $vote */
        $vote = $votes->findOrFail($this->id, $this->actor);

        //投票
        $vote->name = Arr::get($this->attributes, 'name');
        $vote->type = Arr::get($this->attributes, 'type');
        $vote->start_at = Arr::get($this->attributes, 'start_at');
        $vote->end_at = Arr::get($this->attributes, 'end_at');
        $vote->save();

        //选项
        $contents = $this->attributes['contents'];
        foreach ($contents as $content) {
            $id = Arr::get($content, 'id');
            if ($id) {
                //更新
                $bus->dispatchNow(
                    new EditVoteOptions($this->actor, $vote->id, $id, Arr::get($content, 'content'))
                );
            } else {
                //插入
                $bus->dispatchNow(
                    new CreateVoteOptions($this->actor, $vote->id, Arr::get($content, 'content'))
                );
            }
        }
        return $vote;

    }
}
