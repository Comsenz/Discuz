<?php

namespace App\User;

use App\Exceptions\TranslatorException;
use App\Models\UserWechat;
use Carbon\Carbon;
use Discuz\Foundation\Application;
use GuzzleHttp\Client;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Filesystem\Filesystem;

class UserWechatObserver
{
    protected $file;

    protected $app;

    public function __construct(Filesystem $file, Application $app)
    {
        $this->file = $file;
        $this->app = $app;
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

        $httpClient = new Client();

        $response = $httpClient->request('get', $wechatImg);
        if ($response->getStatusCode() != 200) {
            throw new TranslatorException('user_avatar_update_sync_fail');
        }

        // 获取图片二进制
        $img = $response->getBody()->getContents();

        $user->changeAvatar($avatarPath);

        $this->file->put($avatarPath, $img);

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
