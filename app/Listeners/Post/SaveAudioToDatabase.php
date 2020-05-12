<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Listeners\Post;

use App\Commands\Thread\CreateThreadVideo;
use App\Events\Post\Created;
use Illuminate\Contracts\Bus\Dispatcher;
use Psr\Http\Message\ServerRequestInterface;

class SaveAudioToDatabase
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
        $post = $event->post;
        $actor = $event->actor;
        $data = $this->request->getParsedBody()->get('data', []);

        /**
         * 回复 或 长文首贴 可发音频
         */
        if (!$post->is_first || ($post->is_first && $post->thread->type === 1)) {
            $audio = $this->bus->dispatch(
                new CreateThreadVideo($actor, $post, $data)
            );

            $post->setRelation('postAudio', $audio);
        }
    }
}
