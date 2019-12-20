<?php


/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: FileServiceProvider.phphp 28830 2019-09-29 18:04 chenkeke $
 */

namespace App\Providers;

use Discuz\Foundation\AbstractServiceProvider;
use App\Commands\File\Upload;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Filesystem\Factory;
use League\Flysystem\FilesystemInterface;

class FileServiceProvider extends AbstractServiceProvider
{
    /**
     * 注册服务.
     *
     * @return void
     */
    public function register()
    {
        $this->registerFilesystem();
    }

    /**
     * 引导服务.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * 事件处理类
         */
        $events = $this->app->make('events');

        // 订阅事件
        // $events->subscribe(DiscussionMetadataUpdater::class);

        // 监听事件
        // $events->listen(
        //     Renamed::class, DiscussionRenamedLogger::class
        // );
    }

    protected function registerFilesystem()
    {
//        $filesystem = function (Container $app) {
//            return $app->make('filesystem')->disk(config('filesystems.default'))->getDriver();
//        };
//
//        $this->app->when(Upload::class)
//            ->needs(Factory::class)
//            ->give($filesystem);
    }

}