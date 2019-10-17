<?php
declare(strict_types=1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: ListClassifyControllerer.php 28830 2019-09-25 11:13 chenkeke $
 */

namespace App\Api\Controller\Classify;

use App\Api\Serializer\ClassifySerializer;
use App\Repositories\ClassifyRepository;
use App\Searchs\Classify\ClassifySearch;
use Discuz\Api\Controller\AbstractListController;
use App\Commands\Circle\CreateThread;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class ListClassifyController extends AbstractListController
{
    public $serializer = ClassifySerializer::class;

    public function data(ServerRequestInterface $request, Document $document)
    {
        // 获取当前用户
        $actor = $request->getAttribute('actor');

        // 获取请求的参数
        $inputs = $request->getQueryParams();

        // 获取请求的IP
        $ipAddress = Arr::get($request->getServerParams(), 'REMOTE_ADDR', '127.0.0.1');

        $data = $this->searcher->apply(
            new ClassifySearch($actor, $inputs, ClassifyRepository::query())
        )->search()->getMultiple();

        return $data;
    }
}