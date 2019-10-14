<?php
declare(strict_types=1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: DeleteClassify.phphp 28830 2019-09-26 10:10 chenkeke $
 */

namespace App\Commands\Classify;

use App\Events\Classify\Deleting;
use App\Repositories\ClassifyRepository;
use Discuz\Foundation\EventsDispatchTrait;
use Illuminate\Contracts\Events\Dispatcher;

class DeleteClassify
{
    use EventsDispatchTrait;

    /**
     * 数据id.
     *
     * @var int
     */
    public $classifyId;

    /**
     * 操作的用户.
     *
     * @var User
     */
    public $actor;

    /**
     * 用户输入的参数.
     *
     * @var array
     */
    public $data;

    /**
     * @param int $classifyId 数据id.
     * @param User $actor 操作的用户.
     */
    public function __construct($classifyId, $actor = null, array $data = [])
    {
        $this->classifyId = $classifyId;
        $this->actor = $actor;
        $this->data = $data;
    }

    /**
     * @param Dispatcher $events
     * @param ClassifyRepository $repository
     * @return Classify
     */
    public function handle(Dispatcher $events, ClassifyRepository $repository)
    {
        $this->events = $events;

        // 判断有没有权限执行此操作
        // $this->assertCan($this->actor, 'deleteClassify');

        $classify = $repository->findOrFail($this->classifyId, $this->actor);

        $this->events->dispatch(
            new Deleting($classify, $this->actor, $this->data)
        );

        $classify->delete();

        $this->dispatchEventsFor($classify, $this->actor);

        return $classify;
    }
}