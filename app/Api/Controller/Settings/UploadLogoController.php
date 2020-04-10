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
use Discuz\Foundation\Application;
use Discuz\Http\UrlGenerator;
use Exception;
use Illuminate\Contracts\Filesystem\Factory as FileFactory;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Support\Arr;
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
        $verifyFile = new UploadedFile(
            $file->getStream()->getMetadata('uri'),
            $file->getClientFilename(),
            $file->getClientMediaType(),
            $file->getError(),
            true
        );
        $this->validator->make(
            ['logo' => $verifyFile],
            [
                'logo' => [
                    'required',
                    'mimes:jpeg,jpg,png,bmp,gif',
                    'max:5120'
                ]
            ]
        )->validate();

        $fileName = 'logo.'.$verifyFile->getClientOriginalExtension();

        try {
            $this->filesystem->disk('public')->put($fileName, $file->getStream());
        } catch (Exception $e) {
            throw new $e;
        }

        $this->settings->set('logo', $fileName);

        return [
            'key' => 'logo',
            'value' => $this->url->to('/storage/'.$fileName) . '?' . Carbon::now()->timestamp,
            'tag' => 'default'
        ];
    }
}
