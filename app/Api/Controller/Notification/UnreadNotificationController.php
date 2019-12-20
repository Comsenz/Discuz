<?php


/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Notification;

use App\Commands\Notification\UnreadNotification;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Illuminate\Contracts\Bus\Dispatcher as BusDispatcher;

class UnreadNotificationController implements RequestHandlerInterface
{
    /**
     * 命令集调用工具类.
     *
     * @var Dispatcher
     */
    protected $bus;

    public function __construct(BusDispatcher $bus)
    {
        $this->bus = $bus;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {

        // 获取当前用户
        $actor = $request->getAttribute('actor');
        $actor->id = 1;

        // 获取请求的参数
        $inputs = $request->getQueryParams();


        return $this->bus->dispatch(
            new UnreadNotification($actor, $inputs)
        );
    }
}
