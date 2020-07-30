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

use App\Models\Attachment;
use Carbon\Carbon;
use Discuz\Console\AbstractCommand;
use Discuz\Foundation\Application;
use Illuminate\Contracts\Filesystem\Factory as Filesystem;

class AttachmentClearCommand extends AbstractCommand
{
    protected $signature = 'clear:attachment';

    protected $description = '清理本地/COS未使用的附件';

    protected $app;

    protected $attachment;

    protected $filesystem;

    /**
     * AvatarCleanCommand constructor.
     * @param string|null $name
     * @param Application $app
     * @param Attachment $attachment
     * @param Filesystem $filesystem
     */
    public function __construct(string $name = null, Application $app, Attachment $attachment, Filesystem $filesystem)
    {
        parent::__construct($name);

        $this->app = $app;
        $this->attachment = $attachment;
        $this->filesystem = $filesystem;
    }

    public function handle()
    {
        $yesterday = Carbon::yesterday()->toDateTimeString();

        $attachments = $this->attachment->where('type_id', 0)->whereTime('created_at', '<', $yesterday)->get();

        $bar = $this->createProgressBar(count($attachments));

        $bar->start();

        $attachments->map(function ($attachment) use ($bar) {
            $path = $attachment->file_path . '/' . $attachment->attachment;

            $thumb = '';
            // 判断是否是远程文件
            if ($attachment->is_remote) {
                $res = $this->filesystem->disk('attachment_cos')->delete($path);
                $type = 'cos';
            } else {
                $res = $this->filesystem->disk('attachment')->delete($path);
                // 如果是帖子图片,删除本地缩略图
                if ($attachment->type == Attachment::TYPE_OF_IMAGE) {
                    $thumb = $attachment::replaceThumb($path);
                    $this->filesystem->disk('attachment')->delete($thumb);
                }
                $type = 'local';
            }

            // 删除后输出
            if ($res) {
                $this->line('');
                $info = '当前附件ID: ' . $attachment->id;
                $this->question($info);
                $msg = '删除了' . $type . ': ' . $path;
                $this->comment($msg);
                if (!empty($thumb)) {
                    $tMsg = '删除了' . $type . ': ' . $thumb;
                    $this->comment($tMsg);
                }

                $attachment->delete();
            }

            $bar->advance();
        });

        $bar->finish();
    }
}
