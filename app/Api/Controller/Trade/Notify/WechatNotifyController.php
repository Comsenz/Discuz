<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: WechatNotifyController.php xxx 2019-10-16 00:00:00 zhouzhou $
 */

namespace App\Api\Controller\Trade\Notify;

use App\Commands\Trade\Notify\WechatNotify;
use Discuz\Api\Controller\AbstractResourceController;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;
use Zend\Diactoros\Response\XmlResponse;

class WechatNotifyController extends AbstractResourceController
{

    /**
     * {@inheritdoc}
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $document = new Document();
        $data     = $this->data($request, $document);
        return new XmlResponse($data);
    }

    public function data(ServerRequestInterface $request, Document $document)
    {
        return $this->bus->dispatch(
            new WechatNotify($request->getParsedBody())
        );
    }
}
