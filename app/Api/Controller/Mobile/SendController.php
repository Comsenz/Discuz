<?php
declare(strict_types=1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: CreateCircleController.php 28830 2019-09-26 09:47 chenkeke $
 */

namespace App\Api\Controller\Mobile;

use App\Commands\Users\SendMessage;
use Discuz\Foundation\Application;
use Illuminate\Contracts\Bus\Dispatcher as BusDispatcher;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response\EmptyResponse;

class SendController implements RequestHandlerInterface
{

    protected $app;
    protected $bus;
    protected $qcloud;

    public function __construct(Application $app,BusDispatcher $bus)
    {
        $this->app = $app;
        $this->bus = $bus;
        $this->qcloud = $this->app->make('qcloud');

    }
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        // 获取当前用户
        $actor = $request->getAttribute('actor');
        // 获取请求的参数
        $inputs = $request->getParsedBody();

        // 分发创建用户的任务
        $data = $this->bus->dispatch(
            new SendMessage($actor, $inputs->toArray(), $this->qcloud)
        );
//      $this->qcloud->service('sms')->send(18501200870, new SendCodeMessage(['code' => '324234', 'expire' => '333']));
        return new EmptyResponse(204);

    }
}