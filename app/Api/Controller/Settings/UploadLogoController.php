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
use Carbon\Carbon;
use Discuz\Api\Controller\AbstractResourceController;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Auth\Exception\PermissionDeniedException;
use Discuz\Contracts\Setting\SettingsRepository;
use Discuz\Http\UrlGenerator;
use Exception;
use Illuminate\Contracts\Filesystem\Factory as FileFactory;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Tobscure\JsonApi\Document;

class UploadLogoController extends AbstractResourceController
{
    use AssertPermissionTrait;

    /**
     * @var string
     */
    public $serializer = SettingSerializer::class;

    /**
     * @var Factory
     */
    protected $validator;

    /**
     * @var FileFactory
     */
    protected $filesystem;

    /**
     * @var SettingsRepository
     */
    protected $settings;

    /**
     * @var UrlGenerator
     */
    protected $url;

    /**
     * 允许上传的类型
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
     * @param Factory $validator
     * @param FileFactory $filesystem
     * @param SettingsRepository $settings
     * @param UrlGenerator $url
     */
    public function __construct(Factory $validator, FileFactory $filesystem, SettingsRepository $settings, UrlGenerator $url)
    {
        $this->validator = $validator;
        $this->filesystem = $filesystem;
        $this->settings = $settings;
        $this->url = $url;
    }

    /**
     * @param ServerRequestInterface $request
     * @param Document $document
     * @return string[]
     * @throws FileNotFoundException
     * @throws PermissionDeniedException
     * @throws ValidationException
     * @throws Exception
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $actor = $request->getAttribute('actor');

        $this->assertCan($actor, 'setting.site');

        UrlGenerator::setRequest($request);

        $type = Arr::get($request->getParsedBody(), 'type', 'logo');
        $file = Arr::get($request->getUploadedFiles(), 'logo');

        if (! $file) {
            throw new FileNotFoundException('file_not_found');
        }

        $verifyFile = new UploadedFile(
            $file->getStream()->getMetadata('uri'),
            $file->getClientFilename(),
            $file->getClientMediaType(),
            $file->getError(),
            true
        );

        $mimes = [
            'watermark_image' => 'mimes:png',
            'favicon' => 'mimes:jpeg,jpg,png,gif,ico,svg',
        ];

        $this->validator->make(
            ['type' => $type, 'logo' => $verifyFile],
            [
                'type' => [Rule::in($this->allowTypes)],
                'logo' => [
                    'required',
                    $mimes[$type] ?? 'mimes:jpeg,jpg,png,gif',
                    'max:5120'
                ]
            ]
        )->validate();

        $fileName = $type . '.' . $verifyFile->getClientOriginalExtension();

        try {
            // 开启 cos 时，再存一份，优先使用
            if ($this->settings->get('qcloud_cos', 'qcloud')) {
                $cosStream = clone $file->getStream();

                $this->filesystem->disk('cos')->put($fileName, $cosStream);
            }

            $this->filesystem->disk('public')->put($fileName, $file->getStream());
        } catch (Exception $e) {
            throw new $e;
        }

        $this->settings->set($type, $fileName, $type === 'watermark_image' ? 'watermark' : 'default');

        return [
            'key' => 'logo',
            'value' => $this->url->to('/storage/'.$fileName) . '?' . Carbon::now()->timestamp,
            'tag' => 'default'
        ];
    }
}
