<?php

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: BatchUpdateThreadsController.php xxx 2019-10-21 14:08:00 LiuDongdong $
 */

namespace App\Api\Controller\Threads;

use App\Api\Serializer\ThreadSerializer;
use App\Commands\Thread\BatchEditThreads;
use Discuz\Api\Client;
use Discuz\Api\Controller\AbstractListController;
use Illuminate\Contracts\Bus\Dispatcher;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class BatchUpdateThreadsController extends AbstractListController
{
    /**
     * {@inheritdoc}
     */
    public $serializer = ThreadSerializer::class;

    /**
     * {@inheritdoc}
     */
    public $optionalInclude = ['logs'];

    /**
     * @var Dispatcher
     */
    protected $bus;

    /**
     * @var Dispatcher
     */
    protected $apiClient;

    /**
     * @param Dispatcher $bus
     * @param Client $apiClient
     */
    public function __construct(Dispatcher $bus, Client $apiClient)
    {
        $this->bus = $bus;
        $this->apiClient = $apiClient;
    }

    /**
     * {@inheritdoc}
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $actor = $request->getAttribute('actor');
        $data = $request->getParsedBody()->get('data', []);
        $meta = $request->getParsedBody()->get('meta', []);

        // 将操作应用到其他所有页面
        if (isset($meta['query']) && in_array($meta['type'], ['approve', 'ignore', 'delete', 'restore'])) {
            // 减少关联
            $meta['query']['include'] = 'user';

            $response = $this->apiClient->send(ListThreadsController::class, $actor, $meta['query'], []);

            $threads = json_decode($response->getBody(), true);

            $data = $this->getBatchData($meta, $threads['data']);
            $resultMeta = $threads['meta'];
        }

        $result = $this->bus->dispatch(
            new BatchEditThreads($actor, $data)
        );

        $document->setMeta($result['meta']);

        if (isset($resultMeta)) {
            foreach ($resultMeta as $key => $meta) {
                $document->addMeta($key, $meta);
            }
        }

        return $result['data'];
    }

    /**
     * @param $meta
     * @param $threads
     * @return mixed
     */
    protected function getBatchData($meta, $threads)
    {
        if ($meta['type'] == 'approve') {
            $action = ['isApproved' => 1];   // 通过
        } elseif ($meta['type'] == 'ignore') {
            $action = ['isApproved' => 2];   // 忽略
        } elseif ($meta['type'] == 'delete') {
            $action = ['isDeleted' => true];   // 删除
        } elseif ($meta['type'] == 'restore') {
            $action = ['isDeleted' => false];   // 还原
        } else {
            $action = [];
        }

        foreach ($threads as $key => $thread) {
            $threads[$key]['attributes'] = $action;
        }

        return $threads;
    }
}
