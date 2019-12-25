<?php


namespace App\Listeners\Group;


use App\Events\Group\Saving;
use App\Models\Group;

class ChangeDefaultGroup
{
    public function handle(Saving $event)
    {
        Group::query()->update(['default' => 0]);
    }
}
