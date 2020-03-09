<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Threads\Notify;

use App\Commands\Thread\Notify\ThreadVideoNotify;
use Illuminate\Contracts\Bus\Dispatcher;
use Discuz\Api\Controller\AbstractResourceController;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;
use Laminas\Diactoros\Response\XmlResponse;

class ThreadVideoNotifyController extends AbstractResourceController
{
    /**
     * @var Dispatcher
//     */
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
        $log = app('log');
        $log->info('video_notify', $request->getParsedBody()->toArray());
        return $this->bus->dispatch(
            new ThreadVideoNotify($request->getParsedBody())
        );
    }
}
