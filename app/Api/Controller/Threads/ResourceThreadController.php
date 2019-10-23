<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: ResourceThreadController.php xxx 2019-10-10 10:56:00 LiuDongdong $
 */

namespace App\Api\Controller\Threads;

use App\Api\Serializer\ThreadSerializer;
use App\Models\Thread;
use App\Models\User;
use App\Repositories\PostRepository;
use App\Repositories\ThreadRepository;
use App\Searchs\Thread\ThreadSearch;
use Discuz\Api\Controller\AbstractResourceController;
use Discuz\Foundation\Application;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;
use Tobscure\JsonApi\Exception\InvalidParameterException;

class ResourceThreadController extends AbstractResourceController
{
    /**
     * @var ThreadRepository
     */
    protected $thread;

    /**
     * @var PostRepository
     */
    protected $posts;

    /**
     * {@inheritdoc}
     */
    public $serializer = ThreadSerializer::class;

    /**
     * {@inheritdoc}
     */
    public $include = [
        'posts',
        // 'posts.thread',
        // 'posts.user',
        // 'posts.user.groups',
        // 'posts.editedUser',
        // 'posts.deletedUser'
    ];

    /**
     * {@inheritdoc}
     */
    public $optionalInclude = [
        'user',
        // 'lastPostedUser',
        // 'firstPost',
        // 'lastPost'
    ];

    /**
     * {@inheritdoc}
     */
    public function data(ServerRequestInterface $request, Document $document)
    {
        $this->thread = new ThreadRepository;
        $this->posts = new PostRepository;

        $threadId = Arr::get($request->getQueryParams(), 'id');
        $actor = $request->getAttribute('actor');

        $query = $this->thread->query();

        $query->where('id', $threadId);

        $data = $this->searcher->apply(
            new ThreadSearch($actor, [], $query)
        )->search()->getSingle();

        return $data;

        $include = $this->extractInclude($request);

        $thread = $this->thread->findOrFail($threadId, $actor);

        if (in_array('posts', $include)) {
            $postRelationships = $this->getPostRelationships($include);

            $this->includePosts($thread, $request, $postRelationships);
        }

        $thread->load(array_filter($include, function ($relationship) {
            return ! Str::startsWith($relationship, 'posts');
        }));
// dd($thread->all()->toArray());
        return $thread;
    }

    /**
     * @param Thread $thread
     * @param ServerRequestInterface $request
     * @param array $include
     */
    private function includePosts(Thread $thread, ServerRequestInterface $request, array $include)
    {
        $actor = $request->getAttribute('actor');
        $limit = 10; // $this->extractLimit($request);
        $offset = 0; // $this->getPostsOffset($request, $thread, $limit);

        // $allPosts = $this->loadPostIds($thread, $actor);
        $loadedPosts = $this->loadPosts($thread, $actor, $offset, $limit, $include);

        // array_splice($allPosts, $offset, $limit, $loadedPosts);

        // $thread->setRelation('posts', $allPosts);
        $thread->setRelation('posts', $loadedPosts);
    }

    /**
     * @param Thread $thread
     * @param User $actor
     * @return array
     */
    private function loadPostIds(Thread $thread, User $actor)
    {
        return $thread->posts()->whereVisibleTo($actor)->orderBy('created_at')->pluck('id')->all();
    }

    /**
     * @param array $include
     * @return array
     */
    private function getPostRelationships(array $include)
    {
        $prefixLength = strlen($prefix = 'posts.');
        $relationships = [];

        foreach ($include as $relationship) {
            if (substr($relationship, 0, $prefixLength) === $prefix) {
                $relationships[] = substr($relationship, $prefixLength);
            }
        }

        return $relationships;
    }

    /**
     * @param ServerRequestInterface $request
     * @param Thread $thread
     * @param int $limit
     * @return int
     */
    private function getPostsOffset(ServerRequestInterface $request, Thread $thread, $limit)
    {
        $queryParams = $request->getQueryParams();
        $actor = $request->getAttribute('actor');

        if (($near = Arr::get($queryParams, 'page.near')) > 1) {
            $offset = $this->posts->getIndexForNumber($thread->id, $near, $actor);
            $offset = max(0, $offset - $limit / 2);
        } else {
            $offset = $this->extractOffset($request);
        }

        return $offset;
    }

    /**
     * @param Thread $thread
     * @param User $actor
     * @param int $offset
     * @param int $limit
     * @param array $include
     * @return mixed
     */
    private function loadPosts($thread, $actor, $offset, $limit, array $include)
    {
        $query = $thread->posts()->whereVisibleTo($actor);

        $query->orderBy('created_at')->skip($offset)->take($limit)->with($include);

        $posts = $query->get()->all();

        foreach ($posts as $post) {
            $post->thread = $thread;
        }

        return $posts;
    }
}
