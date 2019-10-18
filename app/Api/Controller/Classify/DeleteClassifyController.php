<?php
declare(strict_types=1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: DeleteClassifyControllerer.php 28830 2019-09-26 10:05 chenkeke $
 */

namespace App\Api\Controller\Classify;

use App\Commands\Classify\DeleteClassify;
use Discuz\Api\Controller\AbstractDeleteController;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;
use Zend\Diactoros\Response\EmptyResponse;

class DeleteClassifyController extends AbstractDeleteController
{

    /**
     * @param ServerRequestInterface $request
     */
    public function delete(ServerRequestInterface $request)
    {
        // 获取当前用户
        $actor = $request->getAttribute('actor');

        // 获取请求的参数
        $inputs = $request->getParsedBody();

        $this->bus->dispatch(
            new DeleteClassify($inputs['id'], $actor)
        );

        return new EmptyResponse(204);
    }

    public function data(ServerRequestInterface $request, Document $document)
    {
        // TODO: Implement data() method.
    }
}