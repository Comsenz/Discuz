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
use Discuz\Api\Controller\AbstractResourceController;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class OffIAccountReplyResourceController extends AbstractResourceController
{
    public $serializer = OffIAccountReplySerializer::class;

    public $optionalInclude = [];

    /**
     * @var WechatOffiaccountReplyRepository
     */
    protected $reply;

    public function __construct(WechatOffiaccountReplyRepository $reply)
    {
        $this->reply = $reply;
    }

    /**
     * {@inheritdoc}
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $id = Arr::get($request->getQueryParams(), 'id', 0);

        return $this->reply->findOrFail($id);
    }
}
