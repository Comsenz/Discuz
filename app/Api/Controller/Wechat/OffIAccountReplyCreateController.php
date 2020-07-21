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
use App\Models\WechatOffiaccountReply;
use Discuz\Api\Controller\AbstractCreateController;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Auth\Exception\PermissionDeniedException;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;
use Illuminate\Validation\Factory as Validator;
use Illuminate\Validation\ValidationException;

class OffIAccountReplyCreateController extends AbstractCreateController
{
    use AssertPermissionTrait;

    /**
     * {@inheritdoc}
     */
    public $serializer = OffIAccountReplySerializer::class;

    /**
     * @var Dispatcher
     */
    protected $bus;

    /**
     * @var Validator
     */
    protected $validator;

    /**
     * @param Dispatcher $bus
     * @param Validator $validator
     */
    public function __construct(Dispatcher $bus, Validator $validator)
    {
        $this->bus = $bus;
        $this->validator = $validator;
    }

    /**
     * @param ServerRequestInterface $request
     * @param Document $document
     * @return mixed|void
     * @throws ValidationException
     * @throws PermissionDeniedException
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $this->assertAdmin($request->getAttribute('actor'));

        $type = Arr::get($this->extractFilter($request), 'type');
        $attributes = Arr::get($request->getParsedBody(), 'data.attributes');

        /**
         * 验证参数
         */
        $build = array_merge($attributes, ['type' => $type]);
        $validatorInfo = $this->validator->make($build, [
            'keyword' => 'required',
            'type' => [
                'in:0,1,2',
                // 当0被关注回复1消息回复 都只允许有一条数据
                function ($attribute, $value, $fail) {
                    if ($value != 2) {
                        // exists data
                        if ($bool = WechatOffiaccountReply::where('type', $value)->exists()) {
                            $fail(trans('wechat.wechat_only_one_message_fail'));
                        }
                    }
                }
            ],
        ]);

        if ($validatorInfo->fails()) {
            throw new ValidationException($validatorInfo);
        }

        $reply = WechatOffiaccountReply::build($build);

        $reply->save();

        return $reply;
    }
}
