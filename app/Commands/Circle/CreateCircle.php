<?php
declare(strict_types=1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: CreateCircleExtend.phpnd.php 28830 2019-09-26 10:09 chenkeke $
 */

namespace App\Commands\Circle;


use Exception;
use App\Models\Circle;
use App\Tools\CircleUploadTool;
use App\Events\Circle\Saving;
use App\Commands\Attachment\CreateAttachment;
use App\Commands\CircleExtend\CreateCircleExtend;
use Discuz\Foundation\EventsDispatchTrait;
use Illuminate\Contracts\Bus\Dispatcher as BusDispatcher;
use Illuminate\Contracts\Events\Dispatcher as EventDispatcher;
use Psr\Http\Message\UploadedFileInterface;

class CreateCircle
{
    use EventsDispatchTrait;

    /**
     * 执行操作的用户.
     *
     * @var User
     */
    public $actor;

    /**
     * 创建圈子的数据.
     *
     * @var array
     */
    public $data;

    /**
     * 请求来源的IP地址.
     *
     * @var string
     */
    public $ipAddress;

    /**
     * 初始化命令参数
     *
     * @param User   $actor        执行操作的用户.
     * @param array  $data         创建圈子的数据.
     * @param string $ipAddress    请求来源的IP地址.
     */
    public function __construct($actor, array $data, string $ipAddress)
    {
        $this->actor = $actor;
        $this->data = $data;
        $this->ipAddress = $ipAddress;
    }

    /**
     * 执行命令
     *
     * @param BusDispatcher $bus
     * @param EventDispatcher $events
     * @param CircleUploadTool $uploadTool
     * @return Circle
     * @throws Exception
     */
    public function handle(BusDispatcher $bus, EventDispatcher $events, CircleUploadTool $uploadTool)
    {
        $this->events = $events;

        // 判断有没有权限执行此操作
        // $this->assertCan($this->actor, 'createCircle');

        // 初始圈子数据
        $circle = Circle::creation(
            $this->data['name'],
            '',
            $this->data['description'],
            $this->data['property'],
            $this->ipAddress
        );

        // 触发钩子事件
        $this->events->dispatch(
            new Saving($circle, $this->actor, $this->data)
        );

        // 保存圈子
        $circle->save();

        // 分发创建圈子扩展信息的任务
        try {
            $bus->dispatch(
                new CreateCircleExtend($circle->id, $this->actor, $this->data, $this->ipAddress)
            );
        } catch (Exception $e) {
            $circle->delete();
            throw $e;
        }

        if ($this->data['file'] instanceof UploadedFileInterface)
        {
            $uploadTool->setFile($this->data['file']);
            $uploadTool->setSingleData($circle);

            // 处理上传的圈子图片
            $attachment = $bus->dispatch(
                new CreateAttachment($actor = [], $uploadTool, $this->ipAddress)
            );

            $circle->icon = $uploadTool->getFullName();

            // 保存圈子
            $circle->save();
        }

        // 调用钩子事件
        $this->dispatchEventsFor($circle);

        // 返回数据对象
        return $circle;
    }
}