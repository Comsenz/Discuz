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

namespace App\Api\Controller\Wallet;

use App\Api\Serializer\UserWalletCashSerializer;
use Discuz\Api\Controller\AbstractResourceController;
use Discuz\Auth\Exception\NotAuthenticatedException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;
use App\Repositories\UserWalletCashRepository;
use Discuz\Auth\AssertPermissionTrait;

class ResourceUserWalletCashController extends AbstractResourceController
{
    use AssertPermissionTrait;

    /**
     * {@inheritdoc}
     */
    public $serializer = UserWalletCashSerializer::class;

    /**
     * @var UserWalletCashRepository
     */
    protected $cash;

    /**
     * @param UserWalletCashRepository $cash [description]
     */
    public function __construct(UserWalletCashRepository $cash)
    {
        $this->cash = $cash;
    }

    /**
     * @param ServerRequestInterface $request
     * @param Document $document
     * @return Builder|Model|mixed
     * @throws NotAuthenticatedException
     */
    public function data(ServerRequestInterface $request, Document $document)
    {
        // 获取当前用户
        $actor = $request->getAttribute('actor');
        $this->assertRegistered($actor);
        $id = Arr::get($request->getQueryParams(), 'id');
        return $this->cash->findCashOrFail($id);
    }
}
