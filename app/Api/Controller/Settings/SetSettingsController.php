<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Settings;

use App\Models\Group;
use App\Settings\SettingsRepository;
use App\Settings\SiteRevManifest;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Auth\Exception\PermissionDeniedException;
use Discuz\Foundation\Application;
use Discuz\Qcloud\QcloudManage;
use Discuz\Qcloud\QcloudTrait;
use Discuz\Qcloud\Services\BillingService;
use Discuz\Qcloud\Services\CmsService;
use Exception;
use Illuminate\Support\Carbon;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Illuminate\Contracts\Cache\Repository as CacheRepository;
use Zend\Diactoros\Response\EmptyResponse;
use Illuminate\Support\Arr;

class SetSettingsController implements RequestHandlerInterface
{
    use AssertPermissionTrait, QcloudTrait;

    /**
     * 需要验证的值
     *
     * @var array
     */
    protected $validationQCloud = [
        'qcloud_secret_id',
        'qcloud_secret_key',
    ];

    protected $cache;

    protected $settings;

    protected $siteRevManifest;

    public function __construct(CacheRepository $cache, SettingsRepository $settings, SiteRevManifest $siteRevManifest, Application $app)
    {
        $this->cache = $cache;
        $this->app = $app;
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
//        $this->assertAdmin($request->getAttribute('actor'));
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

        // 判断是否存QCloud验证值是否正确
        $pluck = $settings->pluck('value', 'key');
        if ($pluck->has($this->validationQCloud)) {
            $only = $pluck->only($this->validationQCloud);
            $billing = new BillingService($only);
            $billing->DescribeAccountBalance();
        }

        $siteMode = $settings->where('tag', 'default')
            ->where('key', 'site_mode')->first();

        if(Arr::get($siteMode, 'value') === 'pay')
        {
            $this->changeSiteMode(Group::UNPAID, Carbon::now(), $settings);
        } elseif (Arr::get($siteMode, 'value') === 'public')
        {
            $this->changeSiteMode(Group::MEMBER_ID, '', $settings);
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

    private function changeSiteMode($groupId, $time, &$settings)
    {
        $settings->push([
            'key' => 'site_pay_time',
            'value' => $time,
            'tag' => 'default'
        ]);
    }
}
