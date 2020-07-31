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

use App\Api\Serializer\ForumSettingSerializer;
use App\Models\User;
use Discuz\Api\Controller\AbstractResourceController;
use Discuz\Contracts\Setting\SettingsRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class ForumSettingsController extends AbstractResourceController
{
    /**
     * {@inheritdoc}
     */
    public $serializer = ForumSettingSerializer::class;

    /**
     * {@inheritdoc}
     */
    public $optionalInclude = ['users'];

    /**
     * @var SettingsRepository
     */
    public $settings;

    /**
     * @param SettingsRepository $settings
     */
    public function __construct(SettingsRepository $settings)
    {
        $this->settings = $settings;
    }

    /**
     * {@inheritdoc}
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $filter = $this->extractFilter($request);

        $tag = Str::of(Arr::get($filter, 'tag', ''))->replace(' ', '')->explode(',')->filter();

        if ($tag->contains('agreement')) {
            $agreement = $this->settings->tag('agreement') ?? [];

            $data['agreement'] = [
                'privacy' => (bool) ($agreement['privacy'] ?? false),
                'privacy_content' => $agreement['privacy_content'] ?? '',
                'register' => (bool) ($agreement['register'] ?? false),
                'register_content' => $agreement['register_content'] ?? '',
            ];
        } else {
            $data = [];
        }

        if (in_array('users', $this->extractInclude($request))) {
            $data['users'] = User::orderBy('created_at', 'desc')->limit(5)->get(['id', 'username', 'avatar']);
        }

        return $data + ['id' => 1];
    }
}
