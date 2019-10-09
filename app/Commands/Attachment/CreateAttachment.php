<?php
declare(strict_types=1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: CreateAttachmentAttachment.php 28830 2019-09-29 16:52 chenkeke $
 */

namespace App\Commands\Attachment;

use App\Events\Attachment\Uploading;
use App\Exceptions\UploadException;
use App\Models\Attachment;
use App\Models\Post;
use Discuz\Foundation\EventsDispatchTrait;
use Discuz\Contracts\Tool\UploadTool;
use Exception;
use Illuminate\Contracts\Events\Dispatcher as EventDispatcher;
use Illuminate\Contracts\Filesystem\Factory as FileFactory;
use Illuminate\Database\Eloquent\Model;
use Intervention\Image\ImageManager;
use Psr\Http\Message\UploadedFileInterface;

class CreateAttachment
{
    use EventsDispatchTrait;

    /**
     * 执行操作的用户.
     *
     * @var User
     */
    public $actor;

    /**
     * 上传附件的工具.
     *
     * @var Model
     */
    public $uploadTool;

    /**
     * 请求来源的IP地址.
     *
     * @var string
     */
    public $ipAddress;

    /**
     * 初始化命令参数
     *
     * @param User                  $actor      执行操作的用户.
     * @param UploadTool            $uploadTool 上传附件的工具.
     * @param string                $ipAddress  请求来源的IP地址.
     */
    public function __construct
    (
        $actor,
        UploadTool $uploadTool,
        string $ipAddress
    )
    {
        $this->actor = $actor;
        $this->uploadTool = $uploadTool;
        $this->ipAddress = $ipAddress;

    }

    /**
     * 执行命令
     *
     * @param EventDispatcher $events
     * @param FileFactory $fileFactory
     * @return Attach
     * @throws Exception
     */
    public function handle(EventDispatcher $events)
    {
        $this->events = $events;

        // 判断有没有权限执行此操作
        // $this->assertCan($this->actor, 'uploadFile');

        // 判断上传的文件是否正常
        if ($this->file->getError()){
            throw new UploadException;
        }

        $model = $this->uploadTool->getSingleData();

        $uploadPath = $this->uploadTool->getUploadPath();

        $uploadName = $this->uploadTool->getUploadName();

        $uploadFile = $this->uploadTool->getFile();

        $this->events->dispatch(
            new Uploading($this->actor, $this->uploadTool)
        );

        $this->uploadTool->saveFile();
dd('aaa');
        // 初始附件数据
        $attachment = Attachment::creation(
            0,// $this->actor,
            $model->id,
            $uploadName,
            $uploadPath,
            $uploadFile->getClientFilename(),
            $uploadFile->getSize(),
            $uploadFile->getClientMediaType(),
            0,
            $this->ipAddress
        );

        if ($model instanceof Post) {
            // 保存附件
            $attachment->save();

            // 调用钩子事件
            $this->dispatchEventsFor($attachment);
        }

        return $attachment;
    }
}