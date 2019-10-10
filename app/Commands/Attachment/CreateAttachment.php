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

        $uploadFile = $this->uploadTool->getFile();

        // 判断上传的文件是否正常
        if ($uploadFile->getError()){
            throw new UploadException;
        }

        $extension = pathinfo($uploadFile->getClientFilename(), PATHINFO_EXTENSION);

        $uploadPath = $this->uploadTool->getUploadPath('', true);

        $uploadName = $this->uploadTool->getUploadName($extension, true);

        $this->events->dispatch(
            new Uploading($this->actor, $this->uploadTool)
        );

        $res = $this->uploadTool->saveFile($uploadPath, $uploadName);

        if (!$res){
            throw new UploadException;
        }

        $model = $this->uploadTool->getSingleData();

        // 初始附件数据
        $attachment = Attachment::creation(
            0,// $this->actor,
            $model->id?:0,
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