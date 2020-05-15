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
use Illuminate\Support\Arr;
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
        $type = in_array($type, ['logo', 'header_logo', 'background_image']) ? $type : 'logo';

        // 删除原图
        $this->remove($this->settings->get($type));

        // 设置为空
        $this->settings->set($type, '');

        return [
            'key' => 'logo',
            'value' => '',
            'tag' => 'default'
        ];
    }

    private function remove($file)
    {
        $filesystem = $this->filesystem->disk('public');

        if ($filesystem->has($file)) {
            $filesystem->delete($file);
        }
    }
}
