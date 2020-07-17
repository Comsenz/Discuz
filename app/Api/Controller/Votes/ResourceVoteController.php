<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Votes;

use App\Api\Serializer\VoteSerializer;
use App\Repositories\VoteRepository;
use Discuz\Api\Controller\AbstractResourceController;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class ResourceVoteController extends AbstractResourceController
{
    public $serializer = VoteSerializer::class;

    public $optionalInclude = ['options','logs'];

    public $votes;

    public function __construct(VoteRepository $votes)
    {
        $this->votes = $votes;
    }
    /**
     * {@inheritdoc}
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $id = Arr::get($request->getQueryParams(), 'id', 0);

        return $this->votes->findOrFail($id);
    }
}
