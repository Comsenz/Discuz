<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
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
            'media_url'      => $model->media_url,
            'cover_url'      => $model->cover_url,
            'updated_at'     => $this->formatDate($model->updated_at),
            'created_at'     => $this->formatDate($model->created_at)
        ];

        $urlKey = $this->settings->get('qcloud_vod_url_key', 'qcloud');
        $urlExpire = (int) $this->settings->get('qcloud_vod_url_expire', 'qcloud');
        if ($urlKey && $urlExpire) {
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
