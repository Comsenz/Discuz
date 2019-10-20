<?php


namespace App\Console\Commands;

use Discuz\Console\AbstractCommand;
use Discuz\Foundation\Application;

class StorageLinkCommand extends AbstractCommand
{
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;

        parent::__construct();
    }

    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'storage:link';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '创建 "storage/app/public" 的软连接到 "public/storage"';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        if (file_exists(base_path('public/storage'))) {
            return $this->error('"public/storage" 目录已存在.');
        }

        $this->app->make('files')->link(
            storage_path('app/public'), base_path('public/storage')
        );

        $this->info('目录 [public/storage] 已创建成功.');
    }
}

