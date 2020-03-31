<?php

namespace App\User;

use App\Exceptions\TranslatorException;
use App\Models\UserWechat;
use Carbon\Carbon;
use Discuz\Contracts\Setting\SettingsRepository;
use Discuz\Http\UrlGenerator;
use GuzzleHttp\Client;
use Illuminate\Contracts\Filesystem\Filesystem;

class UserWechatObserver
{
    protected $filesystem;

    protected $app;

    protected $settings;

    protected $url;

    public function __construct(Filesystem $filesystem, SettingsRepository $settings, UrlGenerator $url)
    {
        $this->filesystem = $filesystem;
        $this->settings = $settings;
        $this->url = $url;
    }

    /**
     * 同步微信头像
     *
     * @param UserWechat $userWechat
     * @throws TranslatorException
     */
    public function avatarSync($userWechat)
    {
        $user = $userWechat->user;
        if (empty($user)) {
            return;
        }

        // 判断用户是否有设置头像
        if (!empty($user->avatar_at)) {
            return;
        }

        // 微信存储本地头像 用到的 HttpClient()
        $wechatImg = $userWechat->headimgurl;
        $avatarPath = $userWechat->user_id . '.png';
        $avatarUrl = $userWechat->user_id . '.png';

        // 判断是否开启云储存
        if ($this->settings->get('qcloud_cos', 'qcloud')) {
            $avatarPath = 'public/avatar/' . $avatarPath; // 云目录
            $uri = $this->filesystem->url($avatarPath);
            $avatarUrl = $uri->getScheme() . '://' . $uri->getHost() . $uri->getPath();
        }

        $httpClient = new Client();

        $response = $httpClient->request('get', $wechatImg);
        if ($response->getStatusCode() != 200) {
            throw new TranslatorException('user_avatar_update_sync_fail');
        }

        // 获取图片二进制
        $img = $response->getBody()->getContents();

        $user->changeAvatar($avatarUrl);

        $this->filesystem->put($avatarPath, $img);

        $user->avatar_at = Carbon::now()->toDateTimeString();
        $user->save();
    }

    /**
     * 更新User表头像
     *
     * @param UserWechat $userWechat
     * @throws TranslatorException
     */
    public function updated(UserWechat $userWechat)
    {
        $this->avatarSync($userWechat);
    }
}
