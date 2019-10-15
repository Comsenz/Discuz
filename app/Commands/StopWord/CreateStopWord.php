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
use Illuminate\Support\Collection;
use Illuminate\Validation\Factory as Validator;
use Illuminate\Validation\ValidationException;

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
     * The attributes of the new stop word.
     *
     * @var array
     */
    public $data;

    /**
     * @param User $actor The user performing the action.
     * @param Collection $data The attributes of the new group.
     */
    public function __construct($actor, Collection $data)
    {
        // TODO: User $actor
        $this->actor = $actor;
        $this->data = $data;
    }

    /**
     * @param EventDispatcher $events
     * @param Validator $validator
     * @return StopWord
     * @throws ValidationException
     */
    public function handle(EventDispatcher $events, Validator $validator)
    {
        $this->events = $events;

        // TODO: 权限验证
        // $this->assertCan($this->actor, 'startDiscussion');

        $validator = $validator->make($this->data->all(), [
            'ugc' => 'required|in:{MOD},{BANNED},{REPLACE}',
            'username' => 'required|in:{MOD},{BANNED},{REPLACE}',
            'find' => 'required|unique:stop_words,find|between:1,200',
            'replacement' => 'required|between:1,200',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $stopWord = StopWord::build(
            $this->data->get('ugc'),
            $this->data->get('username'),
            $this->data->get('find'),
            $this->data->get('replacement'),
            $this->actor
        );

        $this->events->dispatch(
            new Saving($stopWord, $this->actor, $this->data->all())
        );

        $stopWord->save();

        $this->dispatchEventsFor($stopWord, $this->actor);

        return $stopWord;
    }
}
