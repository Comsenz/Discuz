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
        // $users = $this->user->HaveAvatar()->whereIn('id', $array)->get();

        $users = $this->user->HaveAvatar()->get();

        $bar = $this->createProgressBar(count($users));

        $bar->start();

        $users->map(function ($user) use ($bar) {

            $img = $user->id . '.png';

            $nowAvatar = $user->getRawOriginal('avatar');

            // 判断是否是cos地址
            if (substr_count($nowAvatar, 'http') > 0) {
                $res = $this->app->make(Factory::class)->disk('avatar')->delete($img);
                $type = 'local';
            } else {
                $cosPath = 'public/avatar/' . $img;
                $res = $this->app->make(Factory::class)->disk('avatar_cos')->delete($cosPath);
                $type = 'cos';
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
