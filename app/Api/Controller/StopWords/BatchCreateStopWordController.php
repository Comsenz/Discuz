<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: BatchCreateStopWordController.php xxx 2019-11-05 18:00:00 LiuDongdong $
 */

namespace App\Api\Controller\StopWords;

use App\Commands\StopWord\BatchCreateStopWord;
use Discuz\Api\JsonApiResponse;
use Illuminate\Contracts\Bus\Dispatcher;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class BatchCreateStopWordController implements RequestHandlerInterface
{
    /**
     * @var Dispatcher
     */
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
        $result = $this->bus->dispatch(
            new BatchCreateStopWord($request->getAttribute('actor'), $request->getParsedBody()->get('data', []))
        );

        $data = [
            'data' => [
                'type' => 'stop-words',
                'created' => $result->get('created', 0),    // 新建数量
                'updated' => $result->get('updated', 0),    // 修改数量
                'unique' => $result->get('unique', 0),      // 重复数量
            ],
        ];

        return new JsonApiResponse($data);
    }
}
