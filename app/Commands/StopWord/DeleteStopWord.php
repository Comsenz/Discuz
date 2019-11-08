<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: DeleteStopWord.php xxx 2019-11-06 16:39:00 LiuDongdong $
 */

namespace App\Commands\StopWord;

use App\Events\StopWord\Deleting;
use App\Models\StopWord;
use App\Models\User;
use App\Repositories\StopWordRepository;
use App\Repositories\ThreadRepository;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Foundation\EventsDispatchTrait;
use Exception;
use Illuminate\Contracts\Events\Dispatcher;

class DeleteStopWord
{
    use AssertPermissionTrait;
    use EventsDispatchTrait;

    /**
     * The ID of the stop word to delete.
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
     * 暂未用到，留给插件使用
     *
     * @var array
     */
    public $data;

    /**
     * @param int $stopWordId
     * @param User $actor
     * @param array $data
     */
    public function __construct($stopWordId, User $actor, array $data = [])
    {
        $this->stopWordId = $stopWordId;
        $this->actor = $actor;
        $this->data = $data;
    }

    /**
     * @param Dispatcher $events
     * @param StopWordRepository $stopWords
     * @return StopWord
     * @throws Exception
     */
    public function handle(Dispatcher $events, StopWordRepository $stopWords)
    {
        $this->events = $events;

        $stopWord = $stopWords->findOrFail($this->stopWordId, $this->actor);

        $this->assertCan($this->actor, 'delete', $stopWord);

        $this->events->dispatch(
            new Deleting($stopWord, $this->actor, $this->data)
        );

        $stopWord->delete();

        $this->dispatchEventsFor($stopWord, $this->actor);

        return $stopWord;
    }
}
