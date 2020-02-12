<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Posts;

use App\Api\Serializer\PostSerializer;
use App\Exceptions\TranslatorException;
use App\Models\Post;
use App\Repositories\PostRepository;
use Discuz\Api\Controller\AbstractResourceController;
use Discuz\Auth\AssertPermissionTrait;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class ResourcePostController extends AbstractResourceController
{
    use AssertPermissionTrait;

    /**
     * {@inheritdoc}
     */
    public $serializer = PostSerializer::class;

    /**
     * @var Post
     */
    public $post;

    public $posts;

    /**
     * {@inheritdoc}
     */
    public $include = ['user'];

    /**
     * {@inheritdoc}
     */
    public $optionalInclude = [];

    /**
     * ResourcePostController constructor.
     * @param Post $post
     * @param PostRepository $posts
     */
    public function __construct(Post $post, PostRepository $posts)
    {
        $this->post = $post;
        $this->posts = $posts;
    }

    /**
     * @param ServerRequestInterface $request
     * @param Document $document
     * @return mixed
     * @throws TranslatorException
     * @throws \Tobscure\JsonApi\Exception\InvalidParameterException
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $id = Arr::get($request->getQueryParams(), 'id');
        $actor = $request->getAttribute('actor');
        $load = array_merge($this->extractInclude($request), ['comment_posts']);

        $post = $this->posts->findOrFail($id);
        if ($post->is_first || $post->is_comment) {
            throw new TranslatorException('post_not_fond');
        }

        // 查询点评List 及其关联模型
        if (in_array('comment_posts', $load)) {
            $commentPostRelationships = $this->getPostRelationships($load);

            $reply = $this->post->query()->whereVisibleTo($actor)->where([
                'reply_post_id' => $id,
                'is_comment' => true,
            ])->get();
            $reply->load($commentPostRelationships);

            // 设置 回复回帖的所有内容
            $post->setRelation('commentPosts', $reply);
        }

        $load = array_diff($load, ['comment_posts']);
        $post->load($load);

        return $post;
    }

    /**
     * @param array $include
     * @return array
     */
    private function getPostRelationships(array $include)
    {
        $prefixLength = strlen($prefix = 'comment_posts.');
        $relationships = [];

        foreach ($include as $relationship) {
            if (substr($relationship, 0, $prefixLength) === $prefix) {
                $relationships[] = substr($relationship, $prefixLength);
            }
        }

        return $relationships;
    }
}
