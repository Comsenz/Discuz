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
        $data = [];
        $user_uin = '';
        $event->settings->each(function ($setting) use (&$data, &$user_uin) {
            $key = Arr::get($setting, 'key');
            $value = (bool) Arr::get($setting, 'value');
            $tag = Arr::get($setting, 'tag');
            if ($tag != 'qcloud') {
                return true;
            }
            $action = $value ? 'on' : 'off';
            switch ($key) {
                case 'qcloud_close':
                case 'qcloud_app_id':
                case 'qcloud_secret_id':
                case 'qcloud_secret_key':
                    //云API
                    if (empty($user_uin)) {
                        $user_uin = $this->MsUserInfo();
                        if (!isset($user_uin['UserUin'])) {
                            $user_uin = '';
                            return false;
                        }
                    }
                    if ($key != 'qcloud_close') {
                        $action = ((bool) $this->settings->get('qcloud_close', 'qcloud')) ? 'on' : 'off';
                    }

                    $port = $this->request->getUri()->getPort();
                    $siteUrl = $this->request->getUri()->getScheme() . '://' . $this->request->getUri()->getHost().(in_array($port, [80, 443, null]) ? '' : ':'.$port);

                    $data['site'] = [
                        'action' => $action,
                        'url' => $siteUrl,
                        'uin' => $user_uin['UserUin'],
                    ];
                    break;
                case 'qcloud_cms_image':
                    //图片内容安全
                    $data['ims'] = [
                        'action' => $action
                    ];
                    break;
                case 'qcloud_cms_text':
                    //文本内容安全
                    $data['tms'] = [
                        'action' => $action
                    ];
                    break;
                case 'qcloud_sms':
                case 'qcloud_sms_app_id':
                case 'qcloud_sms_app_key':
                case 'qcloud_sms_template_id':
                case 'qcloud_sms_sign':
                    if ($key != 'qcloud_sms') {
                        $action = ((bool) $this->settings->get('qcloud_sms', 'qcloud')) ? 'on' : 'off';
                    }
                    //短信
                    $data['sms'] = [
                        'action' => $action,
                        'appid' => $this->settings->get('qcloud_sms_app_id', 'qcloud')
                    ];
                    break;
                case 'qcloud_faceid':
                    //实名认证
                    $data['faceid'] = [
                        'action' => $action
                    ];
                    break;
                case 'qcloud_cos':
                case 'qcloud_cos_bucket_name':
                case 'qcloud_cos_bucket_area':
                case 'qcloud_cos_cdn_url':
                    //对象存储
                    if ($key != 'qcloud_cos') {
                        $action = ((bool) $this->settings->get('qcloud_cos', 'qcloud')) ? 'on' : 'off';
                    }
                    $data['cos'] = [
                        'action' => $action,
                        'bucket' => $this->settings->get('qcloud_cos_bucket_name', 'qcloud'),
                        'region' => $this->settings->get('qcloud_cos_bucket_area', 'qcloud')
                    ];
                    break;
                case 'qcloud_vod':
                case 'qcloud_vod_sub_app_id':
                case 'qcloud_vod_transcode':
                case 'qcloud_vod_cover_template':
                case 'qcloud_vod_ext':
                case 'qcloud_vod_size':
                    //视频
                    if ($key != 'qcloud_vod') {
                        $action = ((bool) $this->settings->get('qcloud_vod', 'qcloud')) ? 'on' : 'off';
                    }
                    $data['vod'] = [
                        'action' => $action,
                        'subappid' => $this->settings->get('qcloud_vod_sub_app_id', 'qcloud')
                    ];
                    break;
                case 'qcloud_captcha':
                case 'qcloud_captcha_app_id':
                case 'qcloud_captcha_secret_key':
                case 'qcloud_captcha_ticket':
                case 'qcloud_captcha_randstr':
                    //验证码
                    if ($key != 'qcloud_captcha') {
                        $action = ((bool) $this->settings->get('qcloud_captcha', 'qcloud')) ? 'on' : 'off';
                    }
                    $data['captcha'] = [
                        'action' => $action,
                        'appid' => $this->settings->get('qcloud_captcha_app_id', 'qcloud')
                    ];
                    break;
                default:
                    break;
            }

        });
        if (!empty($data)) {
            $data['site_id'] = $this->settings->get('site_id', 'default');
            try {
                $this->qcloudReport($data)->then(function (ResponseInterface $response) {
                    //$response->getBody()->getContents();
                })->wait();
            } catch (Exception $e) {

            }
        }
    }
}
