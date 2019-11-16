<?php
declare (strict_types = 1);

/**
 *      Discuz & Tencent Cloud
 *      This is NOT a freeware, use is subject to license terms
 *
 *      Id: NotificationController.php xxx 2019-11-06 18:24:00 yanchen $
 */

namespace App\Api\Controller\Notification;

use App\Api\Serializer\NotificationSerializer;
use App\Commands\Notification\ListNotification;
use App\Models\User;
use Discuz\Api\Controller\AbstractListController;
use Discuz\Auth\AssertPermissionTrait;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;
use Illuminate\Contracts\Bus\Dispatcher;

class ListNotificationController extends AbstractListController
{
    use AssertPermissionTrait;

    /**
     * @param Dispatcher $bus
     */
    public function __construct(Dispatcher $bus)
    {
        $this->bus = $bus;
    }

    /**
     * {@inheritdoc}
     */
    public $serializer = NotificationSerializer::class;

    protected function data(ServerRequestInterface $request, Document $document)
    {
        $user = User::find(1);
        $user->notify(new TopicReplied());


        // 获取当前用户
        $actor = $request->getAttribute('actor');
        $actor->id = 1;
        // 获取请求的参数
        $inputs = $request->getQueryParams();

        return $this->bus->dispatch(
            new ListNotification($actor, $inputs)
        );
    }
}
