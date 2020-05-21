<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\System;

use Discuz\Auth\Exception\PermissionDeniedException;
use Discuz\Console\Kernel;
use Discuz\Api\Controller\AbstractResourceController;
use Discuz\Foundation\Application;
use Discuz\Http\DiscuzResponseFactory;
use Illuminate\Support\Arr;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class CallCommandController extends AbstractResourceController
{
    /**
     * @var Application
     */
    protected $app;

    /**
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * {@inheritdoc}
     * @throws PermissionDeniedException
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $document = new Document();
        $this->data($request, $document);
        return DiscuzResponseFactory::EmptyResponse();
    }

    /**
     * @param ServerRequestInterface $request
     * @param Document $document
     * @return mixed|void
     * @throws PermissionDeniedException
     */
    public function data(ServerRequestInterface $request, Document $document)
    {
        $commandList = [
            'finance:create',
            'clear:avatar',
            'clear:attachment',
            'clear:video',
            'order:query',
        ];
        $command = Arr::get($request->getParsedBody(), 'data.attributes.command');
        if (!in_array($commandList, $command)) {
            throw new PermissionDeniedException();
        }
        $this->app->make(Kernel::class)->call($command);
    }
}
