<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\System;

use App\Console\Commands\AttachmentClearCommand;
use App\Console\Commands\AvatarClearCommand;
use App\Console\Commands\FinanceCreateCommand;
use App\Console\Commands\QueryWechatOrderConmmand;
use App\Console\Commands\VideoClearCommand;
use Discuz\Auth\Exception\PermissionDeniedException;
use Discuz\Console\Kernel;
use Discuz\Api\Controller\AbstractResourceController;
use Discuz\Foundation\Application;
use Discuz\Http\DiscuzResponseFactory;
use Illuminate\Support\Arr;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\Console\Application as ConsoleApplication;
use Tobscure\JsonApi\Document;

class CallCommandController extends AbstractResourceController
{
    /**
     * @var Application
     */
    protected $app;

    /**
     * @var ConsoleApplication
     */
    protected $console;

    /**
     * @param Application $app
     * @param ConsoleApplication $console
     */
    public function __construct(Application $app, ConsoleApplication $console)
    {
        $this->app = $app;
        $this->console = $console;
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
            'finance:create'    => FinanceCreateCommand::class,
            'clear:avatar'      => AvatarClearCommand::class,
            'clear:attachment'  => AttachmentClearCommand::class,
            'clear:video'       => VideoClearCommand::class,
            'order:query'       => QueryWechatOrderConmmand::class,
        ];
        $command = Arr::get($request->getQueryParams(), 'name');
        if (!Arr::has($commandList, $command)) {
            throw new PermissionDeniedException();
        }
        $this->console->add($this->app->make($commandList[$command]));
        $kernel = $this->app->make(Kernel::class);
        $kernel->setDisco($this->console);
        $kernel->call($command);
    }
}
