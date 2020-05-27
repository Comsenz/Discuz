<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Console\Commands;

use App\Models\User;
use Discuz\Console\AbstractCommand;
use Discuz\Foundation\Application;
use Illuminate\Contracts\Filesystem\Factory;

class AvatarClearCommand extends AbstractCommand
{
    protected $signature = 'clear:avatar';

    protected $description = '清理本地/COS未使用的头像';

    protected $app;

    protected $user;

    /**
     * AvatarCleanCommand constructor.
     * @param string|null $name
     * @param Application $app
     * @param User $user
     */
    public function __construct(string $name = null, Application $app, User $user)
    {
        parent::__construct($name);

        $this->app = $app;
        $this->user = $user;
    }

    public function handle()
    {
        // test data
        // $array = [130, 344, 343, 342];
        // $users = $this->user->hasAvatar()->whereIn('id', $array)->get();

        $users = $this->user->hasAvatar()->get();

        $bar = $this->createProgressBar(count($users));

        $bar->start();

        $users->map(function ($user) use ($bar) {
            $img = $user->id . '.png';

            $nowAvatar = $user->getRawOriginal('avatar');

            // 判断是否是 Cos 地址（如果是 Cos 就删除本地文件，否则删除 Local 文件）
            if (strpos($nowAvatar, '://') === false) {
                $cosPath = 'public/avatar/' . $img;
                $res = $this->app->make(Factory::class)->disk('avatar_cos')->delete($cosPath);
                $type = 'cos';
            } else {
                $res = $this->app->make(Factory::class)->disk('avatar')->delete($img);
                $type = 'local';
            }

            // 删除后输出
            if ($res) {
                $info = '当前值: ' . $nowAvatar;
                $this->question($info);

                $msg = '删除了' . $type . ': ' . $img;
                $this->comment($msg);
            }

            $bar->advance();
        });

        $bar->finish();
    }
}
