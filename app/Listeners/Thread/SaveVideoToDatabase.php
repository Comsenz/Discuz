<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Listeners\Thread;

use App\Commands\Thread\CreateThreadVideo;
use App\Events\Thread\Created;
use App\Models\Thread;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;

class SaveVideoToDatabase
{
    /**
     * @var ServerRequestInterface
     */
    public $request;

    /**
     * @var Dispatcher
     */
    public $bus;

    public function __construct(ServerRequestInterface $request, Dispatcher $bus)
    {
        $this->request = $request;
        $this->bus = $bus;
    }

    /**
     * @param Created $event
     */
    public function handle(Created $event)
    {
        $thread = $event->thread;
        $actor = $event->actor;
        $data = Arr::get($this->request->getParsedBody(), 'data', []);

        $fileId = Arr::get($data, 'attributes.file_id', '');

        if ($fileId && $thread->type === Thread::TYPE_OF_VIDEO) {
            $video = $this->bus->dispatch(
                new CreateThreadVideo($actor, $thread, $data)
            );

            $thread->setRelation('threadVideo', $video);
        }
    }
}
