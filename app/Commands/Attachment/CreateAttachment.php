<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Commands\Attachment;

use App\Censor\Censor;
use App\Models\User;
use App\Settings\SettingsRepository;
use App\Tools\AttachmentUploadTool;
use App\Events\Attachment\Uploading;
use App\Exceptions\UploadException;
use App\Models\Attachment;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Auth\Exception\PermissionDeniedException;
use Discuz\Foundation\EventsDispatchTrait;
use Discuz\Http\Exception\UploadVerifyException;
use GuzzleHttp\Psr7\Uri;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Psr\Http\Message\UploadedFileInterface;

class CreateAttachment
{
    use AssertPermissionTrait;
    use EventsDispatchTrait;

    const FIX_WIDTH = 600;

    /**
     * 执行操作的用户.
     *
     * @var User
     */
    public $actor;

    /**
     * 上传的附件.
     *
     * @var UploadedFileInterface
     */
    public $file;

    /**
     * 请求来源的IP地址.
     *
     * @var string
     */
    public $ipAddress;

    /**
     * 请求来源的IP地址.
     *
     * @var string
     */
    public $isGallery;

    /**
     * 是否合法 0 不合法 1 合法
     *
     * @var int
     */
    public $isApproved = 0;

    /**
     * 初始化命令参数
     *
     * @param User $actor 执行操作的用户.
     * @param UploadedFileInterface $file
     * @param string $ipAddress 请求来源的IP地址.
     * @param bool $isGallery    是否是帖子图片
     */
    public function __construct(
        $actor,
        UploadedFileInterface $file,
        string $ipAddress,
        bool $isGallery = false
    ) {
        $this->actor = $actor;
        $this->file = $file;
        $this->ipAddress = $ipAddress;
        $this->isGallery = $isGallery;
    }

    /**
     * 执行命令
     *
     * @param Dispatcher $events
     * @param AttachmentUploadTool $uploadTool
     * @param SettingsRepository $settings
     * @param Censor $censor
     * @return Attachment
     * @throws PermissionDeniedException
     * @throws UploadException
     * @throws UploadVerifyException
     * @throws \Illuminate\Contracts\Filesystem\FileExistsException
     */
    public function handle(Dispatcher $events, AttachmentUploadTool $uploadTool, SettingsRepository $settings, Censor $censor)
    {
        $this->events = $events;

        // 判断有没有权限执行此操作
        $this->assertCan($this->actor, 'attachment.create.' . (int) $this->isGallery);

        // 判断上传的文件是否正常
        if ($this->file->getError()) {
            throw new UploadException();
        }

        $uploadTool->upload($this->file, 'public/attachment');

        $this->events->dispatch(
            new Uploading($this->actor, $this->file)
        );

        $type = $settings->get($this->isGallery ? 'support_img_ext' : 'support_file_ext');
        $type = $type ? explode(',', $type) : [];

        // 将数据库存的 Mb 转换为 bytes
        $size = $settings->get('support_max_size', 'default', 0) * 1024 * 1024;

        $uploadFile = $uploadTool->save($type, $size);

        if (! $uploadFile) {
            throw new UploadException();
        }

        $isRemote = 0;

        if(Arr::get($uploadFile, 'url') instanceof Uri) {
            $isRemote = 1;
        }

        // 生成缩略图
        if ($this->isGallery && !$isRemote) {
            $imgPath = Arr::get($uploadFile, 'path');

            $img = (new ImageManager())->make($imgPath);

            $img->resize(self::FIX_WIDTH, null, function ($constraint) {
                $constraint->aspectRatio();     // 保持纵横比
                $constraint->upsize();          // 避免文件变大
            })->save();
        }

        // 检测敏感图
        if (Str::before($this->file->getClientMediaType(), '/') == 'image') {
            $filePathName = $isRemote ? Arr::get($uploadFile, 'url') : Arr::get($uploadFile, 'path');

            $censor->checkImage($filePathName);
            if ($censor->isMod) {
                $this->isApproved = 0;
            }
        }

        $uploadPath = $uploadTool->getUploadPath();

        $uploadName = $uploadTool->getUploadName();

        // 初始附件数据
        $attachment = Attachment::creation(
            $this->actor->id,
            0,
            $this->isGallery,
            $this->isApproved,
            $uploadName,
            $uploadPath,
            $this->file->getClientFilename(),
            $this->file->getSize(),
            $this->file->getClientMediaType(),
            $isRemote,
            $this->ipAddress
        );

        // 保存附件
        $attachment->save();

        // 调用钩子事件
        $this->dispatchEventsFor($attachment);

        return $attachment;
    }
}
