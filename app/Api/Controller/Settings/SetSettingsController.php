<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Settings;

use App\Models\Group;
use App\Settings\SettingsRepository;
use App\Settings\SiteRevManifest;
use App\Validators\SetSettingValidator;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Auth\Exception\PermissionDeniedException;
use Discuz\Foundation\Application;
use Discuz\Qcloud\QcloudTrait;
use Discuz\Qcloud\Services\BillingService;
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

    protected $validator;

    public function __construct(CacheRepository $cache, SettingsRepository $settings, SiteRevManifest $siteRevManifest, Application $app, SetSettingValidator $validator)
    {
        $this->cache = $cache;
        $this->app = $app;
        $this->settings = $settings;
        $this->siteRevManifest = $siteRevManifest;
        $this->validator = $validator;
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
        $siteAuthorScale = $settings->where('tag', 'default')->where('key', 'site_author_scale')->first();
        $siteMasterScale = $settings->where('tag', 'default')->where('key', 'site_master_scale')->first();

        // 只要传了其中一个，就检查分成比例相加是否为 10
        if ($siteAuthorScale || $siteMasterScale) {
            $sum = Arr::get($siteAuthorScale, 'value', 0)
                + Arr::get($siteMasterScale, 'value', 0);

            if ($sum != 10) {
                throw new Exception('scale_sum_not_10');
            }
        }

        // 验证QCloud值是否正确
        $Qcloud = $settings->pluck('value', 'key')->only($this->validationQCloud);
        if (!$Qcloud->isEmpty()) {
            $billing = new BillingService($Qcloud);
            $billing->DescribeAccountBalance();
        }

        // 站点模式切换
        $siteMode = $settings->where('tag', 'default')->where('key', 'site_mode')->first();
        $siteMode = Arr::get($siteMode, 'value');

        if ($siteMode && $siteMode != $this->settings->get('site_mode')) {
            if ($siteMode === 'pay') {
                $this->changeSiteMode(Group::UNPAID, Carbon::now(), $settings);
            } elseif ($siteMode === 'public') {
                $this->changeSiteMode(Group::MEMBER_ID, '', $settings);
            }
        }

        /**
         * @property \App\Validators\SetSettingValidator
         */
        $validator = $settings->pluck('value', 'key')->all();
        $this->validator->valid($validator);

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
