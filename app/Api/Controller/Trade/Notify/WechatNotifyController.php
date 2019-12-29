<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Trade\Notify;

use Illuminate\Contracts\Bus\Dispatcher;
use App\Commands\Trade\Notify\WechatNotify;
use Discuz\Api\Controller\AbstractResourceController;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;
use Zend\Diactoros\Response\XmlResponse;

class WechatNotifyController extends AbstractResourceController
{
    /**
     * @var Dispatcher
     */
    protected $bus;

    /**
     * @param Dispatcher $bus
     */
    public function __construct(Dispatcher $bus)
    {
        $this->bus = $bus;
    }

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
