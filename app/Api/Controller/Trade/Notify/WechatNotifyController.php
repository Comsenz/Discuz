<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: WechatNotifyController.php xxx 2019-10-16 00:00:00 zhouzhou $
 */

namespace App\Api\Controller\Trade\Notify;


use Discuz\Api\Controller\AbstractResourceController;
use App\Api\Serializer\PayOrderSerializer;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response\XmlResponse;
use Tobscure\JsonApi\Document;
use App\Commands\Trade\Notify\WechatNotify;

class WechatNotifyController extends AbstractResourceController
{

	/**
     * {@inheritdoc}
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $document = new Document();
        $data = $this->data($request, $document);
        return new XmlResponse($data);
    }

	public function data(ServerRequestInterface $request, Document $document)
    {
    	return $this->bus->dispatch(
            new WechatNotify($request->getParsedBody())
        );
    }
}

