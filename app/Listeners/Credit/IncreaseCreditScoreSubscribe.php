<?php


namespace App\Listeners\Credit;


use App\Events\Credit\IncreaseCreditScore;
use Illuminate\Contracts\Events\Dispatcher;

class IncreaseCreditScoreSubscribe
{

    public function subscribe(Dispatcher $dispatcher)
    {
        $dispatcher->listen(IncreaseCreditScore::class, IncreaseCreditScoreListener::class);
    }

}
