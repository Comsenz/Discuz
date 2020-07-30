<?php

/**
 * Copyright (C) 2020 Tencent Cloud.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
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
