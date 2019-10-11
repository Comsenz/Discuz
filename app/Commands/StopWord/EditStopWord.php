<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: EditStopWord.php xxx 2019-10-10 16:47:00 LiuDongdong $
 */

namespace App\Commands\StopWord;

use App\Events\StopWord\Saving;
use App\Models\StopWord;
use App\Models\User;
use Discuz\Foundation\EventsDispatchTrait;
use Illuminate\Contracts\Events\Dispatcher as EventDispatcher;
use Illuminate\Support\Arr;

class EditStopWord
{
    use EventsDispatchTrait;

    /**
     * The ID of the stop word to edit.
     *
     * @var int
     */
    public $stopWordId;

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
     * @param int $stopWordId The ID of the stop word to edit.
     * @param User $actor The user performing the action.
     * @param array $data The attributes of the new group.
     */
    public function __construct($stopWordId, $actor, array $data)
    {
        // TODO: User $actor
        $this->stopWordId = $stopWordId;
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

        $stopWord = StopWord::findOrFail($this->stopWordId);

        $stopWord->ugc = Arr::get($this->data, 'ugc');
        $stopWord->username = Arr::get($this->data, 'username');
        $stopWord->find = Arr::get($this->data, 'find');
        $stopWord->replacement = Arr::get($this->data, 'replacement');

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
