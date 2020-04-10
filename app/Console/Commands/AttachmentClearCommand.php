<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
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
        // test data
        // $array = [2413, 2414, 2415, 2416];
        // $attachments = $this->attachment->where('post_id', 0)->whereIn('id', $array)->get();

        $yesterday = Carbon::yesterday()->toDateTimeString();

        $attachments = $this->attachment->where('post_id', 0)->whereTime('created_at', '<', $yesterday)->get();

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
                if ($attachment->is_gallery) {
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
