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

namespace App\Api\Controller\Posts;

use App\Api\Serializer\CommentPostSerializer;
use App\Api\Serializer\PostSerializer;
use App\BlockEditor\BlocksParser;
use App\BlockEditor\Exception\BlockInvalidException;
use App\Commands\Post\CreatePost;
use App\Models\Post;
use Discuz\Api\Controller\AbstractCreateController;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class CreatePostController extends AbstractCreateController
{
    /**
     * {@inheritdoc}
     */
    public $serializer = PostSerializer::class;

    /**
     * {@inheritdoc}
     */
    public $include = [
        'user',
        'thread',
    ];

    /**
     * @var Dispatcher
     */
    protected $bus;

    /**
     * @param Dispatcher $bus
     */
    public function __construct(Dispatcher $bus)
    {
        $this->bus = $bus;
    }

    /**
     * {@inheritdoc}
     * @throws BlockInvalidException
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $actor = $request->getAttribute('actor');
        $data = $request->getParsedBody()->get('data', []);
        $threadId = Arr::get($data, 'relationships.thread.data.id');
        $ip = ip($request->getServerParams());
        $port = Arr::get($request->getServerParams(), 'REMOTE_PORT', 0);
        $BlocksParser = new BlocksParser(collect(Arr::get($data, 'attributes.content')), new Post());

        $isComment = (bool) Arr::get($data, 'attributes.isComment');

        if ($isComment) {
            $this->serializer = CommentPostSerializer::class;

            $this->include = array_merge($this->include, ['replyUser']);
        }

        return $this->bus->dispatch(
            new CreatePost($BlocksParser, $BlocksParser->parse(), $threadId, $actor, $data, $ip, $port)
        );
    }
}
