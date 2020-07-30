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
use Discuz\Api\Controller\AbstractResourceController;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Contracts\Setting\SettingsRepository;
use Illuminate\Contracts\Filesystem\Factory;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class DeleteLogoController extends AbstractResourceController
{
    use AssertPermissionTrait;

    /**
     * {@inheritdoc}
     */
    public $serializer = SettingSerializer::class;

    /**
     * @var Factory
     */
    protected $filesystem;

    /**
     * @var SettingsRepository
     */
    protected $settings;

    /**
     * 允许删除的类型
     *
     * @var array
     */
    protected $allowTypes = [
        'background_image',
        'watermark_image',
        'header_logo',
        'logo',
        'favicon',
    ];

    /**
     * @param Factory $filesystem
     * @param SettingsRepository $settings
     */
    public function __construct(Factory $filesystem, SettingsRepository $settings)
    {
        $this->filesystem = $filesystem;
        $this->settings = $settings;
    }

    /**
     * @param ServerRequestInterface $request
     * @param Document $document
     * @return mixed
     * @throws \Discuz\Auth\Exception\PermissionDeniedException
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $actor = $request->getAttribute('actor');

        $this->assertCan($actor, 'setting.site');

        $type = Arr::get($request->getParsedBody(), 'type', 'logo');

        // 类型
        $type = in_array($type, $this->allowTypes) ? $type : 'logo';

        // 设置项 Tag
        $settingTag = $type === 'watermark_image' ? 'watermark' : 'default';

        // 删除原图
        $this->remove($this->settings->get($type, $settingTag));

        // 设置为空
        $this->settings->set($type, '', $settingTag);

        return [
            'key' => 'logo',
            'value' => '',
            'tag' => 'default'
        ];
    }

    /**
     * @param string $file
     */
    private function remove($file)
    {
        $filesystem = $this->filesystem->disk('public');

        if ($filesystem->has($file)) {
            $filesystem->delete($file);
        }
    }
}
