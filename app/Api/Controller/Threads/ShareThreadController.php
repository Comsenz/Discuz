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

namespace App\Api\Controller\Threads;

use App\Models\Post;
use App\Models\Thread;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class ShareThreadController extends ResourceThreadController
{
    /**
     * {@inheritdoc}
     */
    public $include = [
        'user',
        'firstPost',
    ];

    /**
     * {@inheritdoc}
     */
    public $optionalInclude = [
        'category',
        'firstPost.images',
        'firstPost.attachments',
        'firstPost.likedUsers',
        'rewardedUsers',
    ];

    protected function data(ServerRequestInterface $request, Document $document)
    {
        $threadId = Arr::get($request->getQueryParams(), 'id');
        $include = $this->extractInclude($request);

        /** @var Thread $thread */
        $thread = Thread::query()
            ->where('is_approved', Thread::APPROVED)
            ->whereNull('deleted_at')
            ->findOrFail($threadId);

        $thread->loadMissing($include);

        $thread->firstPost->content = $thread->price > 0
            ? ''
            : Str::of($thread->firstPost->content)->substr(0, Post::SUMMARY_LENGTH)->finish(Post::SUMMARY_END_WITH);

        return $thread;
    }
}
