<?php
declare (strict_types = 1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: UnreadNotificationController.php xxx 2019-11-06 18:24:00 yanchen $
 */

namespace App\Api\Controller\Notification;

use App\Commands\Notification\UnreadNotification;
use Discuz\Api\JsonApiResponse;
use Discuz\Foundation\Application;
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

    public function __construct( BusDispatcher $bus)
    {
        $this->bus = $bus;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface {

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
