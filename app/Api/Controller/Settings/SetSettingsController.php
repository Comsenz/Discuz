<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Settings;

use App\Settings\SettingsRepository;
use App\Settings\SiteRevManifest;
use Discuz\Auth\AssertPermissionTrait;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Zend\Diactoros\Response\EmptyResponse;
use Illuminate\Support\Arr;

class SetSettingsController implements RequestHandlerInterface
{
    use AssertPermissionTrait;

    protected $cache;

    protected $settings;

    protected $siteRevManifest;

    public function __construct(CacheRepository $cache, SettingsRepository $settings, SiteRevManifest $siteRevManifest)
    {
        $this->cache = $cache;
        $this->settings = $settings;
        $this->siteRevManifest = $siteRevManifest;
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws \Discuz\Auth\Exception\PermissionDeniedException
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $this->assertAdmin($request->getAttribute('actor'));
        $settings = $request->getParsedBody()->get('data', []);

        foreach ($settings as $setting) {
            $key = Arr::get($setting, 'attributes.key');
            $value = Arr::get($setting, 'attributes.value');
            $tag = Arr::get($setting, 'attributes.tag');

            // 分成比例相加必须为 10
            if ($key == 'site_author_scale' && $tag == 'default') {
                $this->settings->set('site_master_scale', 10 - $value, $tag);
            }
            if ($key == 'site_master_scale' && $tag == 'default') {
                $this->settings->set('site_author_scale', 10 - $value, $tag);
            }

            $this->settings->set($key, $value, $tag);
        }

        $this->siteRevManifest->put('settings', $this->settings->all());

        return new EmptyResponse(204);
    }
}
