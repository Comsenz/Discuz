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

namespace App\Api\Controller\Notification;

use App\Api\Serializer\NotificationTplSerializer;
use App\Models\NotificationTpl;
use Discuz\Api\Controller\AbstractResourceController;
use Discuz\Auth\AssertPermissionTrait;
use Illuminate\Contracts\Validation\Factory;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use RuntimeException;
use Tobscure\JsonApi\Document;

class UpdateNotificationTplController extends AbstractResourceController
{
    use AssertPermissionTrait;

    public $serializer = NotificationTplSerializer::class;

    /**
     * @var Factory
     */
    protected $validation;

    /**
     * @param Factory $validation
     */
    public function __construct(Factory  $validation)
    {
        $this->validation = $validation;
    }

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
                    'title'     => 'filled',
                    'content'     => 'filled',
                ])->validate();

                if ($title = Arr::get($attributes, 'title')) {
                    $notificationTpl->title = $title;
                }
                if ($content = Arr::get($attributes, 'content')) {
                    $notificationTpl->content = $content;
                }
                break;
            case 1:
                if ($notificationTpl->status == 1) {
                    $this->validation->make($attributes, [
                        'template_id'     => 'filled',
                    ])->validate();
                }

                if (Arr::has($attributes, 'template_id')) {
                    $notificationTpl->template_id = Arr::get($attributes, 'template_id');
                }
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
