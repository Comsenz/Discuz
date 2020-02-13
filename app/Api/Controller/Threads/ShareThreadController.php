<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Threads;

use App\Models\Post;
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

        $thread = $this->thread->findOrFail($threadId);

        $thread->loadMissing($include);

        $thread->firstPost->content = Str::limit($thread->firstPost->content, Post::SUMMARY_LENGTH);

        return $thread;
    }
}
