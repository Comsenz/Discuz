<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Settings;

use App\Events\Setting\Saving;
use App\Models\Group;
use App\Settings\SettingsRepository;
use App\Validators\SetSettingValidator;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Auth\Exception\PermissionDeniedException;
use Discuz\Http\DiscuzResponseFactory;
use Discuz\Qcloud\QcloudTrait;
use Exception;
use Illuminate\Contracts\Events\Dispatcher as Events;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class SetSettingsController implements RequestHandlerInterface
{
    use AssertPermissionTrait;
    use QcloudTrait;

    /**
     * @var Events
     */
    protected $events;

    /**
     * @var SettingsRepository
     */
    protected $settings;

    /**
     * @var SetSettingValidator
     */
    protected $validator;

    /**
     * @param Events $events
     * @param SettingsRepository $settings
     * @param SetSettingValidator $validator
     */
    public function __construct(Events $events, SettingsRepository $settings, SetSettingValidator $validator)
    {
        $this->events = $events;
        $this->settings = $settings;
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

        // 转换为以 tag + key 为键的集合，即可去重又方便取用
        $settings = collect($request->getParsedBody()->get('data', []))
            ->pluck('attributes')
            ->keyBy(function ($item) {
                return $item['tag'] . '_' . $item['key'];
            });

        /**
         * TODO: 将不同功能的设置放到监听器中验证，不要全写在 SetSettingValidator
         * @example CheckWatermarkSetting::class
         * @deprecated SetSettingValidator::class（建议整改后废弃）
         */
        $this->events->dispatch(new Saving($settings));

        // 分成比例检查
        $siteAuthorScale = $settings->pull('default_site_author_scale');
        $siteMasterScale = $settings->pull('default_site_master_scale');

        // 只要传了其中一个，就检查分成比例相加是否为 10
        if ($siteAuthorScale || $siteMasterScale) {
            $siteAuthorScale = abs(Arr::get($siteAuthorScale, 'value', 0));
            $siteMasterScale = abs(Arr::get($siteMasterScale, 'value', 0));
            $sum = $siteAuthorScale + $siteMasterScale;

            if ($sum === 10) {
                $this->setSiteScale($siteAuthorScale, $siteMasterScale, $settings);
            } else {
                throw new Exception('scale_sum_not_10');
            }
        }

        // 站点模式切换
        $siteMode = $settings->get('default_site_mode');
        $siteMode = Arr::get($siteMode, 'value');

        if (in_array($siteMode, ['pay', 'public']) && $siteMode != $this->settings->get('site_mode')) {
            if ($siteMode === 'pay') {
                $this->changeSiteMode(Group::UNPAID, Carbon::now(), $settings);
            } elseif ($siteMode === 'public') {
                $this->changeSiteMode(Group::MEMBER_ID, '', $settings);
            }
        }

        // 扩展名统一改为小写
        $settings->transform(function ($item, $key) {
            $extArr = ['default_support_img_ext','default_support_file_ext','qcloud_qcloud_vod_ext'];
            if (in_array($key, $extArr)) {
                $item['value'] = strtolower($item['value']);
            }
            return $item;
        });


        /**
         * @see SetSettingValidator
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

        return DiscuzResponseFactory::EmptyResponse(204);
    }

    /**
     * @param int $groupId
     * @param Carbon $time
     * @param Collection $settings
     */
    private function changeSiteMode($groupId, $time, &$settings)
    {
        $settings->put('default_site_pay_time', [
            'key' => 'site_pay_time',
            'value' => $time,
            'tag' => 'default'
        ]);
    }

    /**
     * 设置分成比例
     *
     * @param int $siteAuthorScale
     * @param int $siteMasterScale
     * @param Collection $settings
     */
    private function setSiteScale(int $siteAuthorScale, int $siteMasterScale, &$settings)
    {
        $settings->put('default_site_author_scale', [
            'key' => 'site_author_scale',
            'value' => $siteAuthorScale,
            'tag' => 'default',
        ]);

        $settings->put('default_site_master_scale', [
            'key' => 'site_master_scale',
            'value' => $siteMasterScale,
            'tag' => 'default',
        ]);
    }
}
