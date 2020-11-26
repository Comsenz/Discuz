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
use App\Models\Post;
use App\Models\SessionToken;
use App\Models\Thread;
use App\Models\User;
use App\Repositories\AttachmentRepository;
use App\Settings\SettingsRepository;
use Carbon\Carbon;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Auth\Exception\PermissionDeniedException;
use Discuz\Auth\Guest;
use Discuz\Http\DiscuzResponseFactory;
use Exception;
use GuzzleHttp\Client as HttpClient;
use Illuminate\Contracts\Filesystem\Factory as Filesystem;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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
     * @param Filesystem $filesystem
     */
    public function __construct(AttachmentRepository $attachments, SettingsRepository $settings, Filesystem $filesystem)
    {
        $this->attachments = $attachments;
        $this->settings = $settings;
        $this->filesystem = $filesystem;
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws OrderException
     * @throws PermissionDeniedException
     * @throws Exception
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $attachmentId = Arr::get($request->getQueryParams(), 'id');
        $page = (int)Arr::get($request->getQueryParams(), 'page');
        $token = SessionToken::get(Arr::get($request->getQueryParams(), 't', ''));
        $isAttachment =  Arr::get($request->getQueryParams(), 'isAttachment', 0);

        if ($token) {
            $user = $token->user ?? new Guest();
        } else {
            throw new PermissionDeniedException();
        }

        $attachment = $this->getAttachment($attachmentId, $user);

        if ($attachment->is_remote) {
            $httpClient = new HttpClient();
            $url = $this->filesystem->disk('attachment_cos')->temporaryUrl($attachment->full_path, Carbon::now()->addDay());
            if ($page) {
                $url .= '&ci-process=doc-preview&page='.$page;
            }
            try {
                $response = $httpClient->get($url);
            } catch (Exception $e) {
                if (Str::contains($e->getMessage(), 'FunctionNotEnabled')) {
                    throw new Exception('qcloud_file_preview_unset');
                } else {
                    throw new ModelNotFoundException();
                }
            }
            if ($response->getStatusCode() == 200) {
                if ($page) {
                    //预览
                    $data = [
                        'data' => [
                            'X-Total-Page' => $response->getHeader('X-Total-Page')[0],
                            'image' => 'data:image/jpeg;base64,'.base64_encode($response->getBody())
                        ],
                    ];
                    return DiscuzResponseFactory::JsonResponse($data);
                } else {
                    //下载
                    if ($isAttachment) {
                        $header = [
                            'Content-Disposition' => 'attachment;filename=' . $attachment->file_name,
                        ];
                    } else {
                        $header = [
                            'Content-Type' => $attachment->file_type,
                            'Content-Disposition' => 'inline;filename=' . $attachment->file_name,
                        ];
                    }

                    return DiscuzResponseFactory::FileStreamResponse(
                        $response->getBody(),
                        200,
                        $header
                    );
                }
            } else {
                throw new ModelNotFoundException();
            }
        } else {
            $filePath = storage_path('app/' . $attachment->full_path);

            // 帖子图片直接显示
            if ($attachment->type == Attachment::TYPE_OF_IMAGE) {
                // 是否要获取缩略图
                if (Arr::get($request->getQueryParams(), 'thumb') === 'true') {
                    $thumb = Str::replaceLast('.', '_thumb.', $filePath);

                    // 缩略图是否存在
                    if (! file_exists($thumb)) {
                        $img = (new ImageManager())->make($filePath);

                        $img->resize(Attachment::FIX_WIDTH, Attachment::FIX_WIDTH, function ($constraint) {
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
     * @param int $attachmentId
     * @param User $actor
     * @return Attachment|null
     * @throws OrderException
     * @throws PermissionDeniedException
     */
    protected function getAttachment($attachmentId, $actor)
    {
        $attachment = $this->attachments->findOrFail($attachmentId, $actor);

        $post = $attachment->post;

        // 是否有权查看帖子
        $this->assertCan($actor, 'view', $attachment->post ?? new Post());

        Thread::setStateUser($actor);

        $thread = $post->thread;

        // 主题是否收费
        if ($thread->price > 0 && ! $thread->is_paid) {
            throw new OrderException('order_post_not_found');
        }

        // 主题附件是否付费
        if ($thread->attachment_price > 0 && ! $thread->is_paid_attachment) {
            throw new OrderException('order_post_not_found');
        }

        return $attachment;
    }
}
