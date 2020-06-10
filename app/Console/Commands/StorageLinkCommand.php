<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

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
    protected $description = '创建从“公共/存储”到“存储/应用/公共”的符号链接';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        if (file_exists(public_path('storage'))) {
            return $this->error('The "public/storage" directory already exists.');
        }

        $this->app->make('files')->link(
            storage_path('app/public'),
            public_path('storage')
        );

        $this->info('The [public/storage] directory has been linked.');
    }
}
