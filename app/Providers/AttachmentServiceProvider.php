<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Providers;

use App\Events\Attachment\Saving;
use App\Events\Attachment\Uploaded;
use App\Events\Attachment\Uploading;
use App\Listeners\Attachment\AddWatermarkToImage;
use App\Listeners\Attachment\CheckImage;
use App\Listeners\Attachment\LocalImageHandler;
use Discuz\Foundation\AbstractServiceProvider;
use Illuminate\Contracts\Support\DeferrableProvider;

class AttachmentServiceProvider extends AbstractServiceProvider implements DeferrableProvider
{
    /**
     * {@inheritdoc}
     */
    public function register()
    {
    }

    /**
     * @return void
     */
    public function boot()
    {
        $events = $this->app->make('events');

        $events->listen(Uploading::class, AddWatermarkToImage::class);
        $events->listen(Uploaded::class, LocalImageHandler::class);

        $events->listen(Saving::class, CheckImage::class);
    }
}
