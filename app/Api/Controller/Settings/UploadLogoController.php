<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Settings;

use App\Api\Serializer\SettingSerializer;
use Carbon\Carbon;
use Discuz\Api\Controller\AbstractResourceController;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Contracts\Setting\SettingsRepository;
use Discuz\Http\UrlGenerator;
use Exception;
use Illuminate\Contracts\Filesystem\Factory as FileFactory;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
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
     * @return array
     * @throws \Discuz\Auth\Exception\PermissionDeniedException
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $actor = $request->getAttribute('actor');

        $this->assertCan($actor, 'setting.site');

        UrlGenerator::setRequest($request);

        $type = Arr::get($request->getParsedBody(), 'type', 'logo');
        $file = Arr::get($request->getUploadedFiles(), 'logo');

        $verifyFile = new UploadedFile(
            $file->getStream()->getMetadata('uri'),
            $file->getClientFilename(),
            $file->getClientMediaType(),
            $file->getError(),
            true
        );

        $this->validator->make(
            ['type' => $type, 'logo' => $verifyFile],
            [
                'type' => [Rule::in($this->allowTypes)],
                'logo' => [
                    'required',
                    'mimes:' . ($type === 'watermark_image' ? 'png' : 'jpeg,jpg,png,bmp,gif'),
                    'max:5120'
                ]
            ]
        )->validate();

        $fileName = $type . '.' . $verifyFile->getClientOriginalExtension();

        try {
            $this->filesystem->disk('public')->put($fileName, $file->getStream());
        } catch (Exception $e) {
            throw new $e;
        }

        $this->settings->set($type, $fileName, $type === 'watermark_image' ? 'watermark' : '');

        return [
            'key' => 'logo',
            'value' => $this->url->to('/storage/'.$fileName) . '?' . Carbon::now()->timestamp,
            'tag' => 'default'
        ];
    }
}
