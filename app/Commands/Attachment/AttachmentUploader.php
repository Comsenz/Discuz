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

namespace App\Commands\Attachment;

use App\Models\Attachment;
use Carbon\Carbon;
use Discuz\Filesystem\CosAdapter;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Http\UploadedFile;

class AttachmentUploader
{
    /**
     * @var \League\Flysystem\Filesystem
     */
    protected $filesystem;

    /**
     * @var UploadedFile
     */
    public $file;

    /**
     * @var string
     */
    protected $path = 'public/attachments';

    /**
     * @var array
     */
    protected $options = [
        'visibility' => 'public'
    ];

    /**
     * @var bool
     */
    protected $isRemote;

    /**
     * @var string
     */
    protected $url;

    /**
     * @param Filesystem $filesystem
     */
    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;

        $this->path = $this->path . date('/Y/m/d/');
    }

    /**
     * @param UploadedFile $file
     * @param int $type
     * @param array $options
     */
    public function upload(UploadedFile $file, $type, $options = [])
    {
        $this->file = $file;

        /**
         * 如果类型是 1（帖子图片）并且使用云存储，就使用云上数据处理，生成高斯模糊图。
         * @see https://cloud.tencent.com/document/product/460/18147#.E4.BA.91.E4.B8.8A.E6.95.B0.E6.8D.AE.E5.A4.84.E7.90.86
         */
        if ($type === Attachment::TYPE_OF_IMAGE && $this->isRemote()) {
            [$hash, $extension] = explode('.', $this->file->hashName());

            $options = array_merge($this->options, [
                'header' => [
                    'PicOperations' => json_encode([
                        'rules' => [
                            [
                                'fileid' => md5($hash) . '_blur.' . $extension,
                                'rule' => 'imageMogr2/thumbnail/500x500/blur/40x20',
                            ]
                        ],
                    ]),
                ]
            ]);
        }

        $this->filesystem->put($this->path, $this->file, $options);
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return bool
     */
    public function isRemote()
    {
        $this->isRemote = $this->isRemote ?? $this->filesystem->getAdapter() instanceof CosAdapter;

        return $this->isRemote;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        if (!$this->file->hashName()) {
            return '';
        }

        $fullPath = $this->file->hashName($this->getPath());

        return $this->isRemote()
            ? $this->filesystem->temporaryUrl($fullPath, Carbon::now()->addMinutes(15))
            : $this->filesystem->url($fullPath);
    }
}
