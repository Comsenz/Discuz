<?php
declare(strict_types=1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: CreateCircleController.php 28830 2019-09-26 09:47 chenkeke $
 */

namespace App\Api\Controller\Users;

use App\Oauth\Password;
use App\Commands\Users\CreateUsers;
use Illuminate\Contracts\Bus\Dispatcher as BusDispatcher;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;

class AddUsersController implements RequestHandlerInterface
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

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        // 获取当前用户
        $actor = $request->getAttribute('actor');
        // 获取请求的参数
        $inputs = $request->getParsedBody();

        // 获取请求的IP
        $ipAddress = Arr::get($request->getServerParams(), 'REMOTE_ADDR', '127.0.0.1');

        // 分发创建用户的任务
        $data = $this->bus->dispatch(
            new CreateUsers($actor, $inputs->toArray(), $ipAddress)
        );
        //生成jwt
        /** @var TYPE_NAME $actor */
        $jwt = $this->bus->dispatch(
            new Password($data->id,$request,$data->username,$data->password)
        );
        // 返回结果
        return $jwt;
    }
}