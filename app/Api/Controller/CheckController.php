<?php


namespace App\Api\Controller;


use App\Api\Serializer\QcloudCheckSerializer;
use Discuz\Api\Controller\AbstractResourceController;
use Discuz\Foundation\Application;
use Discuz\Qcloud\QcloudClient;
use Discuz\Qcloud\QcloudTrait;
use Illuminate\Support\Arr;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Tobscure\JsonApi\Document;

class CheckController implements RequestHandlerInterface
{
    use QcloudTrait;

    /**
     * Handles a request and produces a response.
     *
     * May call other collaborating code to generate the response.
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        //使用Qcloud查询余额看是否能请求通过，能通过刚表明配置正确，不能刚直接异常
        $this->describeAccountBalance();

        //检查是否有新版本
        $response = $this->checkVersion([
            'data' => [
                'attributes' => [
                    'version' => Application::VERSION
                ]
            ]
        ]);

        return $response;
    }
}
