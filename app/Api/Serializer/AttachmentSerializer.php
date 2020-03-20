<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Serializer;

use App\Commands\Attachment\CreateAttachment;
use Discuz\Api\Serializer\AbstractSerializer;
use Discuz\Contracts\Setting\SettingsRepository;
use Discuz\Filesystem\CosAdapter;
use Discuz\Http\UrlGenerator;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Tobscure\JsonApi\Relationship;

class AttachmentSerializer extends AbstractSerializer
{
    /**
     * {@inheritdoc}
     */
    protected $type = 'attachments';

    /**
     * @var UrlGenerator
     */
    protected $url;

    /*
     * @var Filesystem
     */
    protected $filesystem;

    /*
     * @var Filesystem
     */
    protected $cosFilesystem;

    /**
     * @param UrlGenerator $url
     * @param Filesystem $filesystem
     * @param SettingsRepository $settings
     */
    public function __construct(UrlGenerator $url, Filesystem $filesystem, SettingsRepository $settings)
    {
        $this->url = $url;
        $this->filesystem = $filesystem;

        $qcloud = $settings->tag('qcloud');
        $config = app('config')->get('filesystems.disks.cos');

        $config['region'] = Arr::get($qcloud, 'qcloud_cos_bucket_area');
        $config['bucket'] = Arr::get($qcloud, 'qcloud_cos_bucket_name');
        $config['ciurl'] = Arr::get($qcloud, 'qcloud_ci_url', '');

        $config['credentials'] = [
            'secretId'  => Arr::get($qcloud, 'qcloud_secret_id'),  //"云 API 密钥 SecretId";
            'secretKey' => Arr::get($qcloud, 'qcloud_secret_key'), //"云 API 密钥 SecretKey";
            'token' => ''
        ];

        $this->cosFilesystem = new \League\Flysystem\Filesystem(new CosAdapter($config));
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultAttributes($model)
    {
        $path = $model->file_path.'/'.$model->attachment;

        if ($model->is_remote) {
            $uri = $this->cosFilesystem->getAdapter()->getUrl($path);
        } else {
            $uri = $this->filesystem->url($path);
        }

        $url = $model->is_remote
            ? $uri->getScheme() . '://' . $uri->getHost() . $uri->getPath()
            : $this->url->to(str_replace('public', '/storage', $model->file_path) . '/' . $model->attachment);

        $fixWidth = CreateAttachment::FIX_WIDTH;

        $attributes = [
            'order' => $model->order,
            'isGallery' => $model->is_gallery,
            'isRemote' => $model->is_remote,
            'url' => $url,
            'attachment' => $model->attachment,
            'extension' => Str::afterLast($model->attachment, '.'),
            'fileName' => $model->file_name,
            'filePath' => $model->file_path,
            'fileSize' => (int)$model->file_size,
            'fileType' => $model->file_type,
        ];

        if ($model->is_gallery) {
            $attributes['thumbUrl'] = $model->is_remote
                ? $this->cosFilesystem->getAdapter()->getPicUrl($path).'?imageMogr2/thumbnail/'.$fixWidth.'x/interlace/0'
                : Str::replaceLast('.', '_thumb.', $url);
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
