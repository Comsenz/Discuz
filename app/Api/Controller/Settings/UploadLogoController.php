<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Settings;

use App\Api\Serializer\SettingSerializer;
use Discuz\Api\Controller\AbstractResourceController;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Contracts\Setting\SettingsRepository;
use Discuz\Foundation\Application;
use Discuz\Http\UrlGenerator;
use Illuminate\Contracts\Filesystem\Factory as FileFactory;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Support\Arr;
use Intervention\Image\ImageManager;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Tobscure\JsonApi\Document;

class UploadLogoController extends AbstractResourceController
{
    use AssertPermissionTrait;

    public $serializer = SettingSerializer::class;

    protected $app;

    protected $validator;

    protected $filesystem;

    protected $settings;

    protected $url;

    public function __construct(Application $app, Factory $validator, FileFactory $filesystem, SettingsRepository $settings, UrlGenerator $url)
    {
        $this->app = $app;
        $this->validator = $validator;
        $this->filesystem = $filesystem;
        $this->settings = $settings;
        $this->url = $url;
    }

    /**
     * @param ServerRequestInterface $request
     * @param Document $document
     * @return mixed|void
     * @throws \Discuz\Auth\Exception\PermissionDeniedException
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $actor = $request->getAttribute('actor');

        $this->assertCan($actor, 'setting.site');

        UrlGenerator::setRequest($request);

        $file = Arr::get($request->getUploadedFiles(), 'logo');

        $tmpFile = tempnam($this->app->storagePath().'/tmp', 'logo');

        $file->moveTo($tmpFile);

        $file = new UploadedFile(
            $tmpFile,
            $file->getClientFilename(),
            $file->getClientMediaType(),
            $file->getSize(),
            $file->getError(),
            true
        );

        $this->validator->make(
            ['logo' => $file],
            [
                'logo' => [
                    'required',
                    'mimes:jpeg,jpg,png,bmp,gif',
                    'max:5120'
                ]
            ]
        )->validate();

        try {
            $image = (new ImageManager())->make($tmpFile);

            if (extension_loaded('exif')) {
                $image->orientate();
            }

            $encodedImage = $image->encode('png');

            $fileName = 'logo.png';

            $this->filesystem->disk('public')->put($fileName, $encodedImage);
        } finally {
            @unlink($tmpFile);
        }

        $this->settings->set('logo', $fileName);

        return [
            'key' => 'logo',
            'value' => $this->url->to('/storage/'.$fileName),
            'tag' => 'default'
        ];
    }
}
