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
use App\Repositories\ClassifyRepository;
use App\Searchs\Classify\ClassifySearch;
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
    protected function data(ServerRequestInterface $request, Document $document)
    {
        // 获取当前用户
        $actor = $request->getAttribute('actor');

        // 获取请求的参数
        $inputs = $request->getQueryParams();

        // 获取请求的IP
        $ipAddress = Arr::get($request->getServerParams(), 'REMOTE_ADDR', '127.0.0.1');

        $query = ClassifyRepository::query();

        $query->where('id', $inputs['id']);

        $data = $this->searcher->apply(
            new ClassifySearch($actor, $inputs, $query)
        )->search()->getSingle();

        return $data;
    }
}
