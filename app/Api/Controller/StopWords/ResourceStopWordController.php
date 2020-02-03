<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\StopWords;

use App\Api\Serializer\StopWordSerializer;
use App\Models\StopWord;
use Discuz\Api\Controller\AbstractResourceController;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class ResourceStopWordController extends AbstractResourceController
{
    /**
     * {@inheritdoc}
     */
    public $serializer = StopWordSerializer::class;

    /**
     * {@inheritdoc}
     */
    public $include = ['user'];

    /**
     * {@inheritdoc}
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        return StopWord::with('user')->findOrFail(Arr::get($request->getQueryParams(), 'id'));
    }
}
