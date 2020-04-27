<?php


namespace App\Listeners\DenyUser;


use App\Commands\Users\DeleteUserFollow;
use App\Events\DenyUsers\Saved;
use Illuminate\Contracts\Bus\Dispatcher;

class DeleteFollow
{
    protected $bus;

    public function __construct(Dispatcher $bus)
    {
        $this->bus = $bus;
    }

    public function handle(Saved $event) {
        $this->bus->dispatch(
            new DeleteUserFollow($event->actor, 0, $event->denyUser->deny_user_id)
        );
    }
}
