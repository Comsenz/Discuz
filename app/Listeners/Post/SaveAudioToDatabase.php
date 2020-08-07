<?php

/**
 * Copyright (C) 2020 Tencent Cloud.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace App\Listeners\Post;

use App\Commands\Thread\CreateThreadVideo;
use App\Events\Post\Created;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Support\Arr;
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
        $data = Arr::get($this->request->getParsedBody(), 'data', []);

        $fileId = Arr::get($data, 'attributes.file_id', '');

        if ($fileId) {
            $audio = $this->bus->dispatch(
                new CreateThreadVideo($actor, $post, $data)
            );

            $post->setRelation('postAudio', $audio);
        }
    }
}
