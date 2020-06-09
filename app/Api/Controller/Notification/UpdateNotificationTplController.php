<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Notification;

use App\Api\Serializer\NotificationTplSerializer;
use App\Models\NotificationTpl;
use Discuz\Api\Controller\AbstractResourceController;
use Discuz\Auth\AssertPermissionTrait;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;
use RuntimeException;

class UpdateNotificationTplController extends AbstractResourceController
{
    use AssertPermissionTrait;

    public $serializer = NotificationTplSerializer::class;

    /**
     * @var Factory
     */
    protected $validation;

    /**
     * @param ServerRequestInterface $request
     * @param Document $document
     * @return mixed
     * @throws \Discuz\Auth\Exception\PermissionDeniedException
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $actor = $request->getAttribute('actor');
        $this->assertPermission($actor->isAdmin());

        $id = Arr::get($request->getQueryParams(), 'id');
        $attributes = Arr::get($request->getParsedBody(), 'data.attributes');

        /** @var NotificationTpl $notificationTpl */
        $notificationTpl = NotificationTpl::find($id);

        switch ($notificationTpl->type) {
            case 0:
                $this->validation->make($attributes, [
                    'title'     => 'required',
                    'content'     => 'required',
                ])->validate();

                if ($title = Arr::get($attributes, 'title')) {
                    $notificationTpl->title = $title;
                }
                if ($content = Arr::get($attributes, 'content')) {
                    $notificationTpl->content = $content;
                }
                break;
            case 1:
                $this->validation->make($attributes, [
                    'template_id'     => 'required',
                ])->validate();

                $notificationTpl->template_id = Arr::get($attributes, 'template_id');

                break;
        }

        if (isset($attributes['status'])) {
            $status = Arr::get($attributes, 'status');
            if ($status == 1 && $notificationTpl->type == 1 && empty($notificationTpl->template_id)) {
                // 验证是否设置模板ID
                throw new RuntimeException('notification_is_missing_template_config');
            }

            $notificationTpl->status = $status;
        }

        $notificationTpl->save();

        return $notificationTpl;
    }
}
