<?php


namespace App\Commands\Topic;


use App\Models\User;
use App\Repositories\TopicRepository;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Foundation\EventsDispatchTrait;
use Illuminate\Contracts\Events\Dispatcher;

class DeleteTopic
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

    public function handle(Dispatcher $events, TopicRepository $topics)
    {
        $this->events = $events;

        $this->assertAdmin($this->actor);

        $topic = $topics->findOrFail($this->id);

        $topic->delete();

        return $topic;
    }

}
