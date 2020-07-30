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

namespace App\Api\Controller\Attachment;

use App\Api\Serializer\AttachmentSerializer;
use App\Exceptions\OrderException;
use App\Models\Attachment;
use App\Models\Order;
use App\Repositories\AttachmentRepository;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Auth\Exception\PermissionDeniedException;
use Discuz\Http\DiscuzResponseFactory;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ResourceAttachmentController implements RequestHandlerInterface
{
    use AssertPermissionTrait;

    /**
     * @var AttachmentRepository
     */
    protected $attachments;

    /**
     * {}
     */
    public $serializer = AttachmentSerializer::class;

    /**
     * @param AttachmentRepository $attachments
     */
    public function __construct(AttachmentRepository $attachments)
    {
        $this->attachments = $attachments;
    }

    /**
     * {@inheritdoc}
     *
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws OrderException
     * @throws PermissionDeniedException
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $attachmentUuid = Arr::get($request->getQueryParams(), 'id');
        $actor = $request->getAttribute('actor');

        $attachment = $this->getAttachment($attachmentUuid, $actor);

        $filePath = storage_path('app/attachment/' . $attachment->attachment);

        // 帖子图片直接显示
        if ($attachment->type == Attachment::TYPE_OF_IMAGE) {
            // 是否要获取缩略图
            if (Arr::get($request->getQueryParams(), 'thumb') === 'true') {
                $thumb = Str::replaceLast('.', '_thumb.', $filePath);

                // 缩略图是否存在
                if (! file_exists($thumb)) {
                    $img = (new ImageManager())->make($filePath);

                    $img->resize(300, null, function ($constraint) {
                        $constraint->aspectRatio();     // 保持纵横比
                        $constraint->upsize();          // 避免文件变大
                    })->save($thumb);
                }

                $filePath = $thumb;
            }

            return DiscuzResponseFactory::FileResponse($filePath);
        }

        return DiscuzResponseFactory::FileResponse($filePath, 200, [
            'Content-Disposition' => 'attachment;filename=' . basename($attachment->file_name),
        ]);
    }

    /**
     * @param $attachmentUuid
     * @param $actor
     * @return Attachment|null
     * @throws OrderException
     * @throws PermissionDeniedException
     */
    protected function getAttachment($attachmentUuid, $actor)
    {
        $attachment = $this->attachments->findOrFail($attachmentUuid, $actor);

        $this->assertCan($actor, 'view.' . $attachment->type, $attachment);

        // 附件是否被绑定到帖子上
        $post = $attachment->post;
        if ($post->deleted_at && ! $actor->isAdmin()) {
            return null;
        }

        // 主题是否收费
        $thread = $post->thread;
        if ($thread->price > 0 && ! $actor->isAdmin()) {
            $order = Order::where('user_id', $actor->id)
                ->where('thread_id', $thread->id)
                ->where('type', Order::ORDER_TYPE_REWARD)
                ->where('status', Order::ORDER_STATUS_PAID)
                ->exists();

            if (! $order) {
                throw new OrderException('order_post_not_found');
            }
        }

        return $attachment;
    }
}
