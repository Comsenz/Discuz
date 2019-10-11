<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: CreateStopWord.php xxx 2019-10-09 15:50:00 LiuDongdong $
 */

namespace App\Commands\StopWord;

use App\Events\StopWord\Saving;
use App\Models\StopWord;
use App\Models\User;
use Discuz\Foundation\EventsDispatchTrait;
use Illuminate\Contracts\Events\Dispatcher as EventDispatcher;
use Illuminate\Support\Arr;

class CreateStopWord
{
    use EventsDispatchTrait;

    /**
     * The user performing the action.
     *
     * @var User
     */
    public $actor;

    /**
     * The attributes of the new group.
     *
     * @var array
     */
    public $data;

    /**
     * @param User $actor The user performing the action.
     * @param array $data The attributes of the new group.
     */
    public function __construct($actor, array $data)
    {
        // TODO: User $actor
        $this->actor = $actor;
        $this->data = $data;
    }

    /**
     * @param EventDispatcher $events
     * @return StopWord
     */
    public function handle(EventDispatcher $events)
    {
        $this->events = $events;

        // TODO: 权限验证
        // $this->assertCan($this->actor, 'startDiscussion');

        $stopWord = StopWord::build(
            Arr::get($this->data, 'ugc'),
            Arr::get($this->data, 'username'),
            Arr::get($this->data, 'find'),
            Arr::get($this->data, 'replacement'),
            $this->actor
        );

        $this->events->dispatch(
            new Saving($stopWord, $this->actor, $this->data)
        );

        // TODO: 数据验证
        // $this->validator->assertValid($stopWord->getAttributes());

        $stopWord->save();

        $this->dispatchEventsFor($stopWord, $this->actor);

        return $stopWord;
    }
}
