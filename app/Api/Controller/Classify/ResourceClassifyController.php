<?php
declare(strict_types=1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: ResourceInviteController.php 28830 2019-10-12 15:46 chenkeke $
 */

namespace App\Api\Controller\Classify;


use App\Api\Serializer\ClassifySerializer;
use App\Models\StopWord;
use Discuz\Api\Controller\AbstractResourceController;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class ResourceClassifyController extends AbstractResourceController
{
    /**
     * {@inheritdoc}
     */
    public $serializer = ClassifySerializer::class;

    /**
     * {@inheritdoc}
     */
    public function data(ServerRequestInterface $request, Document $document)
    {
        return StopWord::findOrFail(Arr::get($request->getQueryParams(), 'id'));
    }
}