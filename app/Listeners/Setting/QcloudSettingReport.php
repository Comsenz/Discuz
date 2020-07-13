<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Listeners\Setting;

use App\Settings\SettingsRepository;
use App\Events\Setting\Saved;
use Illuminate\Support\Arr;
use Discuz\Qcloud\QcloudTrait;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;

class QcloudSettingReport
{
    use QcloudTrait;

    /**
     * @var Request
     */
    public $request;

    /**
     * @var SettingsRepository
     */
    public $settings;

    /**
     * @param Request $request
     * @param SettingsRepository $settings
     */
    public function __construct(Request $request, SettingsRepository $settings)
    {
        $this->request = $request;
        $this->settings = $settings;
    }

    /**
     * @param Saved $event
     */
    public function handle(Saved $event)
    {
        $settings = $event->settings->toArray();
        $tags = array_column($settings, 'tag');
        if (in_array('qcloud', $tags)) {
            try {
                $data = [];
                $qcloud_setting = $this->settings->tag('qcloud');
                $port = $this->request->getUri()->getPort();
                $site_url = $this->request->getUri()->getScheme() . '://' . $this->request->getUri()->getHost() . (in_array($port, [80, 443, null]) ? '' : ':' . $port);
                $site_id = $this->settings->get('site_id', 'default');
                if (empty($site_id)) {
                    try {
                        $this->report(['url' => $site_url])->then(function (ResponseInterface $response) use (&$site_id) {
                            $data = json_decode($response->getBody()->getContents(), true);
                            $site_id = Arr::get($data, 'site_id');
                            $this->settings->set('site_id', $site_id);
                            $this->settings->set('site_secret', Arr::get($data, 'site_secret'));
                        })->wait();
                    } catch (Exception $e) {
                        //
                    }
                }
                $data['site_id'] = $site_id;
                $user_uin = $this->MsUserInfo();
                $data['site'] = [
                    'action' => $this->getAction(Arr::get($qcloud_setting, 'qcloud_close')),
                    'url' => $site_url,
                    'uin' => Arr::get($user_uin, 'UserUin')
                ];
                $data['ims'] = [
                    'action' => $this->getAction(Arr::get($qcloud_setting, 'qcloud_cms_image'))
                ];
                $data['tms'] = [
                    'action' => $this->getAction(Arr::get($qcloud_setting, 'qcloud_cms_text'))
                ];
                $data['sms'] = [
                    'action' => $this->getAction(Arr::get($qcloud_setting, 'qcloud_sms')),
                    'appid' => Arr::get($qcloud_setting, 'qcloud_sms_app_id')
                ];
                $data['faceid'] = [
                    'action' => $this->getAction(Arr::get($qcloud_setting, 'qcloud_faceid')),
                ];
                $data['cos'] = [
                    'action' => $this->getAction(Arr::get($qcloud_setting, 'qcloud_cos')),
                    'bucket' => Arr::get($qcloud_setting, 'qcloud_cos_bucket_name'),
                    'region' => Arr::get($qcloud_setting, 'qcloud_cos_bucket_area')
                ];
                $data['vod'] = [
                    'action' => $this->getAction(Arr::get($qcloud_setting, 'qcloud_vod')),
                    'subappid' => Arr::get($qcloud_setting, 'qcloud_vod_sub_app_id')
                ];
                $data['captcha'] = [
                    'action' => $this->getAction(Arr::get($qcloud_setting, 'qcloud_captcha')),
                    'appid' => Arr::get($qcloud_setting, 'qcloud_captcha_app_id')
                ];
                try {
                    $this->qcloudReport($data)->then(function (ResponseInterface $response) {
                        $response->getBody()->getContents();
                    })->wait();
                } catch (Exception $e) {
                    //
                }
            } catch (Exception $e) {
                //
            }
        }
    }

    private function getAction($value)
    {
        return ((bool) $value) ? 'on' : 'off';
    }
}
