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

namespace App\Api\Controller\Posts;

use App\Api\Serializer\PostSerializer;
use App\Commands\Post\BatchEditPosts;
use Discuz\Api\Client;
use Discuz\Api\Controller\AbstractListController;
use Illuminate\Contracts\Bus\Dispatcher;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class BatchUpdatePostsController extends AbstractListController
{
    /**
     * {@inheritdoc}
     */
    public $serializer = PostSerializer::class;

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

            $response = $this->apiClient->send(ListPostsController::class, $actor, $meta['query'], []);

            $posts = json_decode($response->getBody(), true);

            $data = $this->getBatchData($meta, $posts['data']);
            $resultMeta = $posts['meta'];
        }

        $result = $this->bus->dispatch(
            new BatchEditPosts($actor, $data)
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
     * @param $posts
     * @return mixed
     */
    protected function getBatchData($meta, $posts)
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

        foreach ($posts as $key => $post) {
            $posts[$key]['attributes'] = $action;
        }

        return $posts;
    }
}
