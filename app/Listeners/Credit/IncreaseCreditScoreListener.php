<?php


namespace App\Listeners\Credit;


use App\Events\Credit\IncreaseCreditScore;
use Illuminate\Contracts\Events\Dispatcher;
use App\Listeners\Credit\IncreaseCreditScore as IncreaseCreditScoreLib;
use Illuminate\Database\ConnectionInterface;

class IncreaseCreditScoreListener
{
    protected $bus;

    public function __construct(Dispatcher $bus)
    {
        $this->bus = $bus;
    }

    /**
     *
     * @param Dispatcher $events
     * @param ConnectionInterface $dbs
     */
    public function handle(IncreaseCreditScore $events)
    {
        $this->bus->dispatch(new IncreaseCreditScoreLib($events));
    }




}
