<?php

namespace App\User;

use App\Exceptions\TranslatorException;
use App\Models\UserWechat;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Contracts\Filesystem\Filesystem;

class UserWechatObserver
{
    protected $filesystem;

    protected $app;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
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
        $path = $userWechat->user_id . '.png';
        $avatarPath = $this->filesystem->url($path);

        $httpClient = new Client();

        $response = $httpClient->request('get', $wechatImg);
        if ($response->getStatusCode() != 200) {
            throw new TranslatorException('user_avatar_update_sync_fail');
        }

        // 获取图片二进制
        $img = $response->getBody()->getContents();

        $user->changeAvatar($avatarPath);

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
