<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller;

use Discuz\Auth\AssertPermissionTrait;
use Discuz\Qcloud\QcloudTrait;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class CheckController implements RequestHandlerInterface
{
    use QcloudTrait,AssertPermissionTrait;

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws \Discuz\Auth\Exception\PermissionDeniedException
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $this->assertCan($request->getAttribute('actor'), 'checkVersion');

        //使用Qcloud查询余额看是否能请求通过，能通过刚表明配置正确，不能刚直接异常
        $this->describeAccountBalance();

        //检查是否有新版本，todo 后期优化传site_id 过去验证是否在服务器已经注册 否则更新不了
        $response = $this->checkVersion();

        return $response;
    }
}
