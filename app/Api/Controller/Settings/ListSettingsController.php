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

namespace App\Api\Controller\Settings;

use App\Api\Serializer\SettingSerializer;
use App\Models\Setting;
use Discuz\Api\Controller\AbstractListController;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Contracts\Setting\SettingsRepository;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class ListSettingsController extends AbstractListController
{
    use AssertPermissionTrait;

    public $serializer = SettingSerializer::class;

    protected $settings;

    protected $validation;

    public function __construct(Factory $validation, SettingsRepository $settings)
    {
        $this->settings = $settings;
        $this->validation = $validation;
    }

    /**
     * @param ServerRequestInterface $request
     * @param Document $document
     * @return array|mixed
     * @throws \Discuz\Auth\Exception\PermissionDeniedException
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $this->assertAdmin($request->getAttribute('actor'));

        $filter = $this->extractFilter($request);

        $key = Arr::get($filter, 'key', '');
        $tag = Arr::get($filter, 'tag', 'default');

        return Setting::where([['key', $key], ['tag', $tag]])->get();
    }
}
