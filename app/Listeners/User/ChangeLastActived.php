<?php


namespace App\Listeners\User;


use App\Events\Users\Logind;
use Illuminate\Support\Carbon;

class ChangeLastActived
{
    public function handle(Logind $event)
    {
        $event->user->updated_at = Carbon::now();
        $event->user->save();
    }

}
