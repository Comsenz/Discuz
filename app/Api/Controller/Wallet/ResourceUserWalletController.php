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

use App\Settings\SettingsRepository;
use Illuminate\Contracts\Bus\Dispatcher;
use App\Api\Serializer\UserWalletSerializer;
use Discuz\Api\Controller\AbstractResourceController;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;
use App\Repositories\UserWalletRepository;
use Discuz\Auth\AssertPermissionTrait;

class ResourceUserWalletController extends AbstractResourceController
{
    use AssertPermissionTrait;

    /**
     * {@inheritdoc}
     */
    public $serializer = UserWalletSerializer::class;

    /**
     * {@inheritdoc}
     */
    public $include = [
        'user'
    ];

    /**
     * @var Dispatcher
     */
    protected $bus;

    /**
     * @var UserWalletRepository
     */
    public $wallet;

    /**
     * @var SettingsRepository
     */
    protected $setting;

    /**
     * @param Dispatcher $bus
     * @param SettingsRepository $setting
     * @param UserWalletRepository $wallet
     */
    public function __construct(Dispatcher $bus, SettingsRepository $setting, UserWalletRepository $wallet)
    {
        $this->bus = $bus;
        $this->setting = $setting;
        $this->wallet = $wallet;
    }

    /**
     * {@inheritdoc}
     */
    public function data(ServerRequestInterface $request, Document $document)
    {
        // 获取当前用户
        $actor = $request->getAttribute('actor');
        $this->assertRegistered($actor);
        $data = $this->wallet->findOrFail(Arr::get($request->getQueryParams(), 'user_id'), $request->getAttribute('actor'));

        $data->cash_tax_ratio = $this->setting->get('cash_rate', 'cash', 0);

        return $data;
    }
}
