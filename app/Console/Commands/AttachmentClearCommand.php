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

namespace App\Console\Commands;

use App\Commands\Attachment\DeleteAttachment;
use App\Models\Attachment;
use App\Models\User;
use Carbon\Carbon;
use Discuz\Console\AbstractCommand;
use Illuminate\Contracts\Bus\Dispatcher;

class AttachmentClearCommand extends AbstractCommand
{
    /**
     * {@inheritdoc}
     */
    protected $signature = 'clear:attachment';

    /**
     * {@inheritdoc}
     */
    protected $description = 'Clear local / COS unused attachments';

    /**
     * clear attachment
     */
    protected function handle()
    {
        /** @var Dispatcher $bus */
        $bus = app(Dispatcher::class);

        /** @var User $actor */
        $actor = User::query()->find(1);

        $attachments = Attachment::query()
            ->where('type_id', 0)
            ->where('created_at', '<', Carbon::yesterday())
            ->get();

        $bar = $this->createProgressBar($attachments->count());

        $bar->start();

        $attachments->map(function (Attachment $attachment) use ($bus, $actor, $bar) {
            $bus->dispatch(
                new DeleteAttachment($attachment->id, $actor)
            );

            $bar->advance();
        });

        $bar->finish();
    }
}
