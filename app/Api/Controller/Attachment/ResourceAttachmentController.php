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
use App\Settings\SettingsRepository;
use Carbon\Carbon;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Auth\Exception\PermissionDeniedException;
use Discuz\Http\DiscuzResponseFactory;
use Illuminate\Contracts\Filesystem\Factory as Filesystem;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use GuzzleHttp\Client as HttpClient;

class ResourceAttachmentController implements RequestHandlerInterface
{
    use AssertPermissionTrait;

    /**
     * @var AttachmentRepository
     */
    protected $attachments;

    /**
     * @var SettingsRepository
     */
    protected $settings;

    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * {}
     */
    public $serializer = AttachmentSerializer::class;

    /**
     * @param AttachmentRepository $attachments
     * @param SettingsRepository $settings
     */
    public function __construct(AttachmentRepository $attachments, SettingsRepository $settings, Filesystem $filesystem)
    {
        $this->attachments = $attachments;
        $this->settings = $settings;
        $this->filesystem = $filesystem;
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
        $attachmentId = Arr::get($request->getQueryParams(), 'id');
        $page = (int)Arr::get($request->getQueryParams(), 'page');
        $actor = $request->getAttribute('actor');

        $attachment = $this->getAttachment($attachmentId, $actor);

        if ($attachment->is_remote) {
            $httpClient = new HttpClient();
            $path = Str::finish($attachment->file_path, '/') . $attachment->attachment;
            $url = $this->filesystem->disk('attachment_cos')->temporaryUrl($path, Carbon::now()->addHour());
            if ($page) {
                $url .= '&ci-process=doc-preview&page='.$page;
            }
            $response = $httpClient->get($url);
            if ($response->getStatusCode() == 200) {
                //下载
                $header = [
                    'Content-Disposition' => 'attachment;filename=' . $attachment->file_name,
                ];

                //预览
                if ($page) {
                    $header = [
                        'X-Total-Page' => $response->getHeader('X-Total-Page'),
                        'Content-Type' => $response->getHeader('Content-Type'),
                    ];
                }
                return DiscuzResponseFactory::FileStreamResponse(
                    $response->getBody(),
                    200,
                    $header
                );
            } else {
                throw new ModelNotFoundException();
            }
        } else {
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
                'Content-Disposition' => 'attachment;filename=' . $attachment->file_name,
            ]);
        }
    }

    /**
     * @param $attachmentId
     * @param $actor
     * @return Attachment|null
     * @throws OrderException
     * @throws PermissionDeniedException
     */
    protected function getAttachment($attachmentId, $actor)
    {
        $attachment = $this->attachments->findOrFail($attachmentId, $actor);

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
