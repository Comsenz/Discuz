<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Listeners\Thread;

use App\Commands\Thread\CreateThreadVideo;
use App\Events\Thread\Created;
use Illuminate\Contracts\Bus\Dispatcher;
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
        $data = $this->request->getParsedBody()->get('data', []);

        if ($thread->type === 2) {
            $video = $this->bus->dispatch(
                new CreateThreadVideo($actor, $thread, $data)
            );

            $thread->setRelation('threadVideo', $video);
        }
    }
}
