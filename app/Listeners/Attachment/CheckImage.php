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

namespace App\Listeners\Attachment;

use App\Censor\Censor;
use App\Events\Attachment\Saving;
use App\Models\Attachment;
use App\Settings\SettingsRepository;
use Illuminate\Support\Str;
use Psr\Http\Message\ServerRequestInterface;

class CheckImage
{
    /**
     * @var array
     */
    public $data;

    /**
     * @var SettingsRepository
     */
    public $settings;

    /**
     * @var Censor
     */
    public $censor;

    /**
     * @param ServerRequestInterface $request
     * @param SettingsRepository $settings
     * @param Censor $censor
     */
    public function __construct(ServerRequestInterface $request, SettingsRepository $settings, Censor $censor)
    {
        $this->data = $request->getParsedBody();
        $this->settings = $settings;
        $this->censor = $censor;
    }

    public function handle(Saving $event)
    {
        $uploader = $event->uploader;
        $file = $uploader->file;

        // 检测敏感图
        if (Str::startsWith($file->getMimeType(), 'image/')) {
            $isRemote = $uploader->isRemote();

            $image = $isRemote
                ? $uploader->getUrl()
                : storage_path('app/' . $uploader->getFullPath());

            $this->censor->checkImage($image, $isRemote);

            if ($this->censor->isMod) {
                $event->attachment->is_approved = Attachment::UNAPPROVED;
            }
        }
    }
}
