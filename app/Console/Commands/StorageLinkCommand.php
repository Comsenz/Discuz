<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Console\Commands;

use Illuminate\Console\Command;

class StorageLinkCommand extends Command
{
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
    protected $description = 'Create a symbolic link from "public/storage" to "storage/app/public"';

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

        $this->laravel->make('files')->link(
            storage_path('app/public'),
            public_path('storage')
        );

        $this->info('The [public/storage] directory has been linked.');
    }
}
