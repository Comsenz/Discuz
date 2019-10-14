<?php
declare(strict_types=1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: CreateClassifyControllerer.php 28830 2019-09-26 09:47 chenkeke $
 */

namespace App\Api\Controller\Classify;

use Discuz\Api\Controller\AbstractCreateController;
use App\Api\Serializer\ClassifySerializer;
use App\Commands\Classify\CreateClassify;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class CreateClassifyController extends AbstractCreateController
{
    /**
     * 返回的数据字段和格式.
     *
     * @var Serializer
     */
    public $serializer = ClassifySerializer::class;

    /**
     * 数据操作.
     *
     * @param ServerRequestInterface $request  注入http请求对象
     * @param Document               $document 注入返回数据的文档
     * @return mixed
     */
    public function data(ServerRequestInterface $request, Document $document)
    {
        // 获取当前用户
        $actor = $request->getAttribute('actor');

        // 获取请求的参数
        $inputs = $request->getParsedBody();

        // 获取请求的IP
        $ipAddress = Arr::get($request->getServerParams(), 'REMOTE_ADDR', '127.0.0.1');

        // 分发创建圈子的任务
        $data = $this->bus->dispatch(
            new CreateClassify($actor, $inputs->toArray(), $ipAddress)
        );

        // 返回结果
        return $data;
    }
}