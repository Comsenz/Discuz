<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Commands\Emoji;

use App\Models\Invite;
use App\Models\StopWord;
use Discuz\Foundation\EventsDispatchTrait;
use Exception;
use Illuminate\Contracts\Bus\Dispatcher as BusDispatcher;
use Illuminate\Contracts\Events\Dispatcher as EventDispatcher;
use Illuminate\Support\Arr;

class CreateClassify
{
    use EventsDispatchTrait;

    /**
     * 执行操作的用户.
     *
     * @var User
     */
    public $actor;

    /**
     * 请求的数据.
     *
     * @var array
     */
    public $data;

    /**
     * 初始化命令参数
     *
     * @param User   $actor        执行操作的用户.
     * @param array  $data         请求的数据.
     */
    public function __construct($actor, array $data)
    {
        $this->actor = $actor;
        $this->data = $data;
    }

    /**
     * 执行命令
     *
     * @param BusDispatcher $bus
     * @param EventDispatcher $events
     * @return Invite
     * @throws Exception
     */
    public function handle(BusDispatcher $bus, EventDispatcher $events)
    {
        $this->events = $events;

        // 判断有没有权限执行此操作
        //$this->assertCan($this->actor, 'createInvite');
        $stopWord = StopWord::build(
            Arr::get($this->data, 'attributes.ugc'),
            Arr::get($this->data, 'attributes.username'),
            Arr::get($this->data, 'attributes.find'),
            Arr::get($this->data, 'attributes.replacement'),
            $this->actor
        );

        $this->events->dispatch(
            new \App\Events\StopWord\Saving($stopWord, $this->actor, $this->data)
        );

        $validator->valid($stopWord->getAttributes());

        $stopWord->save();

        $this->dispatchEventsFor($stopWord, $this->actor);

        return $stopWord;
    }
}
