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

namespace App\Api\Controller\Wechat;

use App\Api\Serializer\OffIAccountReplySerializer;
use App\Repositories\WechatOffiaccountReplyRepository;
use Discuz\Api\Controller\AbstractListController;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Auth\Exception\PermissionDeniedException;
use Discuz\Http\UrlGenerator;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;
use Illuminate\Validation\Factory as Validator;

class OffIAccountReplyListController extends AbstractListController
{
    use AssertPermissionTrait;

    /**
     * {@inheritdoc}
     */
    public $serializer = OffIAccountReplySerializer::class;

    /**
     * 传输关系
     *
     * {@inheritdoc}
     */
    public $optionalInclude = [];

    /**
     * 默认关系
     *
     * {@inheritdoc}
     */
    public $include = [

    ];

    /**
     * @var Dispatcher
     */
    protected $bus;

    /**
     * @var UrlGenerator
     */
    protected $url;

    /**
     * @var Validator
     */
    protected $validator;

    /**
     * @var WechatOffiaccountReplyRepository
     */
    protected $reply;

    /**
     * @param Dispatcher $bus
     * @param UrlGenerator $url
     * @param Validator $validator
     * @param WechatOffiaccountReplyRepository $reply
     */
    public function __construct(Dispatcher $bus, UrlGenerator $url, Validator $validator, WechatOffiaccountReplyRepository $reply)
    {
        $this->bus = $bus;
        $this->url = $url;
        $this->validator = $validator;
        $this->reply = $reply;
    }

    /**
     * @param ServerRequestInterface $request
     * @param Document $document
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|mixed
     * @throws PermissionDeniedException
     * @throws \Tobscure\JsonApi\Exception\InvalidParameterException
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $this->assertAdmin($request->getAttribute('actor'));

        $filter = $this->extractFilter($request);

        $limit = $this->extractLimit($request);
        $offset = $this->extractOffset($request);
        $include = $this->extractInclude($request);

        $query = $this->reply->query();

        // 搜索关键词/规则名
        if (Arr::has($filter, 'keyword')) {
            $query->where('name', 'like', '%' . $filter['keyword'] . '%');
            $query->orWhere('keyword', 'like', '%' . $filter['keyword'] . '%');
        }

        $replyCount = $limit > 0 ? $query->count() : null;

        $query->skip($offset)->take($limit)->orderBy('created_at', 'desc');

        $document->addPaginationLinks(
            $this->url->route('reports.list'),
            $request->getQueryParams(),
            $offset,
            $limit,
            $replyCount
        );

        $data = $query->get();

        $data->loadMissing($include);

        $document->setMeta([
            'total' => $replyCount,
            'pageCount' => ceil($replyCount / $limit),
        ]);

        return $data;
    }
}
