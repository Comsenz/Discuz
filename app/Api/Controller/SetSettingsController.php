<?php

namespace App\Api\Controller;

use App\Models\Setting;
use App\Settings\SettingsRepository;
use App\Settings\SiteRevManifest;
use Discuz\Auth\AssertPermissionTrait;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Zend\Diactoros\Response\EmptyResponse;

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
        $settings = $request->getParsedBody();

        foreach ($settings as $key => $value) {
            $this->settings->set($key, $value);
        }

        $this->siteRevManifest->put('settings', $this->settings->all());

        return new EmptyResponse(204);
    }
}
