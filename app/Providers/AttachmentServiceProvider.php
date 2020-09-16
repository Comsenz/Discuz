<?php

/**
 * Copyright (C) 2020 Tencent Cloud.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace App\Providers;

use App\Events\Attachment\Deleted;
use App\Events\Attachment\Saving;
use App\Events\Attachment\Uploaded;
use App\Events\Attachment\Uploading;
use App\Listeners\Attachment\AddWatermarkToImage;
use App\Listeners\Attachment\CheckImage;
use App\Listeners\Attachment\DeleteFile;
use App\Listeners\Attachment\LocalImageHandler;
use App\Listeners\Attachment\OrientateImage;
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

        $events->listen(Uploading::class, OrientateImage::class);
        $events->listen(Uploading::class, AddWatermarkToImage::class);
        $events->listen(Uploaded::class, LocalImageHandler::class);

        $events->listen(Saving::class, CheckImage::class);
        $events->listen(Deleted::class, DeleteFile::class);
    }
}
