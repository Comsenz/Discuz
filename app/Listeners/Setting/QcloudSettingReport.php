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

class QcloudSettingReport
{
    use QcloudTrait;

    public $settings;

    public function __construct(SettingsRepository $settings)
    {
        $this->settings = $settings;
    }
    public function handle(Saved $event)
    {
        $event->settings->each(function ($setting) {
            $key = Arr::get($setting, 'key');
            $value = (bool) Arr::get($setting, 'value');
            $tag = Arr::get($setting, 'tag');
            if ($tag != 'qcloud') {
                return true;
            }
            $data = [];
            $action = $value ? 'on' : 'off';
            switch ($key) {
                case 'qcloud_close':
                    $info = $this->MsUserInfo();
                    //云API
                    if (!isset($info['UserUin'])) {
                        return false;
                    }
                    $data['site'] = [
                        'action' => $action,
                        'url' => $this->settings->get('site_url', 'default'),
                        'uin' => $info['UserUin'],
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
                    //对象存储
                    $data['cos'] = [
                        'action' => $action,
                        'bucket' => $this->settings->get('qcloud_cos_bucket_name', 'qcloud'),
                        'region' => $this->settings->get('qcloud_cos_bucket_area', 'qcloud')
                    ];
                    break;
                case 'qcloud_vod':
                    //视频
                    $data['vod'] = [
                        'action' => $action,
                        'subappid' => $this->settings->get('qcloud_vod_sub_app_id', 'qcloud')
                    ];
                    break;
                case 'qcloud_captcha':
                    //验证码
                    $data['captcha'] = [
                        'action' => $action,
                        'appid' => $this->settings->get('qcloud_captcha_app_id', 'qcloud')
                    ];
                    break;
                default:
                    break;
            }
            if (!empty($data)) {
                $data['site_id'] = $this->settings->get('site_id', 'default');
                try {
                    $this->report($data)->then(function (ResponseInterface $response) {
                      //
                    })->wait();
                } catch (Exception $e) {

                }
            }

        });
    }
}
