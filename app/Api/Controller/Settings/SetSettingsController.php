<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Settings;

use App\Settings\SettingsRepository;
use App\Settings\SiteRevManifest;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Auth\Exception\PermissionDeniedException;
use Exception;
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
     * @throws PermissionDeniedException
     * @throws Exception
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $this->assertAdmin($request->getAttribute('actor'));
        $settings = collect($request->getParsedBody()->get('data', []))->pluck('attributes');

        // 分成比例检查
        $siteAuthorScale = $settings->where('tag', 'default')
            ->where('key', 'site_author_scale')->first();

        $siteMasterScale = $settings->where('tag', 'default')
            ->where('key', 'site_master_scale')->first();

        // 只要传了其中一个，就检查分成比例相加是否为 10
        if ($siteAuthorScale || $siteMasterScale) {
            $sum = Arr::get($siteAuthorScale, 'value', 0)
                + Arr::get($siteMasterScale, 'value', 0);

            if ($sum != 10) {
                throw new Exception('scale_sum_not_10');
            }
        }

        $settings->each(function ($setting) {
            $this->settings->set(
                Arr::get($setting, 'key'),
                Arr::get($setting, 'value'),
                Arr::get($setting, 'tag')
            );
        });

        $this->siteRevManifest->put('settings', $this->settings->all());

        return new EmptyResponse(204);
    }
}
