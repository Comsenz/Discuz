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

use App\Models\Attachment;
use App\Traits\HasPaidContent;
use Carbon\Carbon;
use Discuz\Api\Serializer\AbstractSerializer;
use Discuz\Contracts\Setting\SettingsRepository;
use Illuminate\Contracts\Filesystem\Factory as Filesystem;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Support\Str;
use Tobscure\JsonApi\Relationship;

class AttachmentSerializer extends AbstractSerializer
{
    use HasPaidContent;

    /**
     * {@inheritdoc}
     */
    protected $type = 'attachments';

    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * @var SettingsRepository
     */
    protected $settings;

    /**
     * @var UrlGenerator
     */
    protected $url;

    /**
     * @param Filesystem $filesystem
     * @param SettingsRepository $settings
     * @param UrlGenerator $url
     */
    public function __construct(Filesystem $filesystem, SettingsRepository $settings, UrlGenerator $url)
    {
        $this->filesystem = $filesystem;
        $this->settings = $settings;
        $this->url = $url;
    }

    /**
     * {@inheritdoc}
     *
     * @param Attachment $model
     */
    public function getDefaultAttributes($model)
    {
        $this->paidContent($model);

        if ($model->is_remote) {
            $url = $this->settings->get('qcloud_cos_sign_url', 'qcloud', true)
                ? $this->filesystem->disk('attachment_cos')->temporaryUrl($model->full_path, Carbon::now()->addDay())
                : $this->filesystem->disk('attachment_cos')->url($model->full_path);
        } else {
            $url = $this->filesystem->disk('attachment')->url($model->full_path);
        }

        $attributes = [
            'order'             => $model->order,
            'type'              => $model->type,
            'type_id'           => $model->type_id,
            'isRemote'          => $model->is_remote,
            'isApproved'        => $model->is_approved,
            'url'               => $url,
            'attachment'        => $model->attachment,
            'extension'         => Str::afterLast($model->attachment, '.'),
            'fileName'          => $model->file_name,
            'filePath'          => $model->file_path,
            'fileSize'          => (int) $model->file_size,
            'fileType'          => $model->file_type,
        ];

        // 图片缩略图地址
        if ($model->type == Attachment::TYPE_OF_IMAGE) {
            if ($model->getAttribute('blur')) {
                $attributes['thumbUrl'] = $url;
            } else {
                if ($model->is_remote) {
                    $attributes['thumbUrl'] = $url . (strpos($url, '?') === false ? '?' : '&')
                        . 'imageMogr2/thumbnail/' . Attachment::FIX_WIDTH . 'x' . Attachment::FIX_WIDTH;
                } else {
                    // 缩略图不存在时使用原图
                    $attributes['thumbUrl'] = $this->filesystem->disk('attachment')->exists($model->thumb_path)
                        ? Str::replaceLast('.', '_thumb.', $url)
                        : $url;
                }
            }
        } elseif ($model->type == Attachment::TYPE_OF_ANSWER) {
            $attributes['thumbUrl'] = $url;
        }

        // 绑定首帖的附件，如果是付费或开启了预览，返回后端地址
        if (
            $model->post &&
            $model->post->is_first &&
            (
                ($model->post->thread->price > 0 || $model->post->thread->attachment_price > 0) ||
                $this->settings->get('qcloud_cos_doc_preview', 'qcloud')
            )
        ) {
            $attributes['url'] = $this->url->to('/api/attachments/' . $model->id) . '?t=' .Attachment::getFileToken($this->actor);
        }

        return $attributes;
    }

    /**
     * @param $attachment
     * @return Relationship
     */
    protected function user($attachment)
    {
        return $this->hasOne($attachment, UserSerializer::class);
    }

    /**
     * @param $attachment
     * @return Relationship
     */
    public function post($attachment)
    {
        return $this->hasOne($attachment, PostSerializer::class);
    }
}
