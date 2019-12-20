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
use Illuminate\Contracts\Filesystem\Factory;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class DeleteLogoController extends AbstractResourceController
{
    public $serializer = SettingSerializer::class;

    use AssertPermissionTrait;

    protected $filesystem;

    protected $settings;

    public function __construct(Factory $filesystem, SettingsRepository $settings)
    {
        $this->filesystem = $filesystem;
        $this->settings = $settings;
    }

    protected function data(ServerRequestInterface $request, Document $document)
    {
        $actor = $request->getAttribute('actor');

        $this->assertCan($actor, 'setting.site');

        $this->settings->set('logo', '');
        $this->remove();

        return [
            'key' => 'logo',
            'value' => '',
            'tag' => 'default'
        ];
    }

    private function remove()
    {
        $logoPath = 'logo.png';
        $filesystem = $this->filesystem->disk('public');
        if ($filesystem->has($logoPath)) {
            $filesystem->delete($logoPath);
        }
    }
}
