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

namespace App\Api\Serializer;

use App\Models\ThreadVideo;
use App\Traits\HasPaidContent;
use Carbon\Carbon;
use Discuz\Api\Serializer\AbstractSerializer;
use Discuz\Contracts\Setting\SettingsRepository;
use Illuminate\Support\Str;

class ThreadVideoSerializer extends AbstractSerializer
{
    use HasPaidContent;

    /**
     * {@inheritdoc}
     */
    protected $type = 'thread-video';

    /**
     * @var SettingsRepository
     */
    protected $settings;

    public function __construct(SettingsRepository $settings)
    {
        $this->settings = $settings;
    }

    /**
     * {@inheritdoc}
     *
     * @param ThreadVideo $model
     */
    public function getDefaultAttributes($model)
    {
        $this->paidContent($model);

        $attributes = [
            'user_id'        => $model->user_id,
            'thread_id'      => $model->thread_id,
            'status'         => $model->status,
            'reason'         => $model->reason,
            'file_name'      => $model->file_name,
            'file_id'        => $model->file_id,
            'width'          => $model->width,
            'height'         => $model->height,
            'duration'       => $model->duration,
            'media_url'      => $model->media_url,
            'cover_url'      => $model->cover_url,
            'updated_at'     => $this->formatDate($model->updated_at),
            'created_at'     => $this->formatDate($model->created_at)
        ];

        $urlKey = $this->settings->get('qcloud_vod_url_key', 'qcloud');
        $urlExpire = (int) $this->settings->get('qcloud_vod_url_expire', 'qcloud');
        if ($urlKey && $urlExpire && $model->media_url) {
            $currentTime = Carbon::now()->timestamp;
            $dir = Str::beforeLast(parse_url($model->media_url)['path'], '/') . '/';
            $t = dechex($currentTime+$urlExpire);
            $us = Str::random(10);
            $sign = md5($urlKey . $dir . $t . $us);
            $attributes['media_url'] = $model->media_url . '?t=' . $t . '&us='. $us . '&sign='.$sign;
        }

        return $attributes;
    }
}
