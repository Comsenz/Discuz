<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Topic;

use App\Api\Serializer\TopicSerializer;
use App\Repositories\TopicRepository;
use Discuz\Api\Controller\AbstractResourceController;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class ResourceTopicController extends AbstractResourceController
{
    public $serializer = TopicSerializer::class;

    public $optionalInclude = ['user'];

    public $topics;

    public function __construct(TopicRepository $topics)
    {
        $this->topics = $topics;
    }
    /**
     * {@inheritdoc}
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $id = Arr::get($request->getQueryParams(), 'id', 0);

        return $this->topics->findOrFail($id);
    }
}
