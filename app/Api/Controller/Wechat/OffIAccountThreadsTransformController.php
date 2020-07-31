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

namespace App\Api\Controller\Wechat;

use App\Api\Serializer\ThreadSerializer;
use App\Commands\Attachment\AttachmentUploader;
use App\Commands\Thread\CreateThread;
use App\Models\Attachment;
use App\Models\Category;
use App\Models\Thread;
use Discuz\Api\Controller\AbstractCreateController;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Wechat\EasyWechatTrait;
use GuzzleHttp\Client;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class OffIAccountThreadsTransformController extends AbstractCreateController
{
    use AssertPermissionTrait;
    use EasyWechatTrait;

    /**
     * {@inheritdoc}
     */
    public $serializer = ThreadSerializer::class;

    /**
     * {@inheritdoc}
     */
    public $include = [
        'user',
        'firstPost',
        'firstPost.images',
        'threadVideo',
    ];

    /**
     * @var Dispatcher
     */
    protected $bus;

    /**
     * @var mixed
     */
    protected $easyWechat;

    /**
     * @var Thread
     */
    protected $thread;

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var \League\Flysystem\Filesystem
     */
    protected $filesystem;

    /**
     * @var AttachmentUploader
     */
    protected $upload;

    /**
     * 公众号内容
     *
     * @var
     */
    protected $content;

    /**
     * 附件类型
     * 0帖子附件，1帖子图片，2帖子视频，3帖子音频，4消息图片
     * @var int
     */
    protected $attachmentType = Attachment::TYPE_OF_IMAGE;

    /**
     * @param Dispatcher $bus
     * @param Thread $thread
     * @param Client $client
     * @param Filesystem $filesystem
     */
    public function __construct(Dispatcher $bus, Thread $thread, Client $client, Filesystem $filesystem, AttachmentUploader $upload)
    {
        $this->bus = $bus;
        $this->thread = $thread;
        $this->client = $client;
        $this->filesystem = $filesystem;
        $this->upload = $upload;
        $this->easyWechat = $this->offiaccount();
    }

    /**
     * @param ServerRequestInterface $request
     * @param Document $document
     * @return mixed
     * @throws \Discuz\Auth\Exception\PermissionDeniedException
     * @throws \Exception
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $actor = $request->getAttribute('actor');
        $this->assertAdmin($actor);
        $mediaId = Arr::get($request->getQueryParams(), 'media_id');
        $ip = ip($request->getServerParams());
        $port = Arr::get($request->getServerParams(), 'REMOTE_PORT'); // 端口

        $response = $this->easyWechat->material->get($mediaId);
        if (array_key_exists('errcode', $response)) {
            throw new \Exception($response['errmsg']);
        }
        // 判断素材是否是 news 格式
        if (!array_key_exists('news_item', $response)) {
            throw new \Exception('asset_is_not_news_type');
        }

        $material = array_shift($response['news_item']);
        $this->content = $material['content'];

        // 检测是否有视频 替换成 Video 标签
        $regexVideo = '/<iframe[\s\S]*?data-src="(?<video>.*?)">\s*<\/iframe>/iu';
        if (preg_match_all($regexVideo, $this->content, $matchContent)) {
            // 匹配替换 Video 标签
            collect($matchContent['video'])->each(function ($item, $key) use ($matchContent) {
                $label = '<video>';
                $label .= $item . '</video>';
                $this->content = str_replace($matchContent[$key], $label, $this->content);
            });
        }

        /** @var Category $category 分类 */
        $category = Category::query()->first();

        $build = [
            'attributes' => [
                'content' => $this->content,
                'type' => 1, // 文章类型（0: 文字 1: 帖子 2: 视频 3:图片）
                'title' => $material['title'],
            ],
            'relationships' => [
                'category' => [
                    'data' => [
                        'type' => 'categories',
                        'id' => $category->id,
                    ],
                ],
            ],
        ];

        // 创建主题
        $thread = $this->bus->dispatch(
            new CreateThread($actor, $build, $ip, $port)
        );

        // 获取 post_id
        $post = $thread->firstPost;
        $postId = $post->id;

        // 匹配公众号所有图片
        $regex = '/data-s="(\w|,|.){0,9}"\s*data-src="(?<src>.*?)"\s*data-type="/iu';
        if (preg_match_all($regex, $this->content, $matchContent)) {
            $build = [
                'uuid' => Str::uuid(),
                'user_id' => $actor->id,
                'ip' => $ip,
                'type_id' => $postId,
            ];

            $attachment = new Attachment;
            try {
                collect($matchContent['src'])->each(function ($item) use ($attachment, $build) {
                    $model = clone $attachment;
                    $result = $this->uploadImg($item);
                    $build = array_merge($build, $result);
                    $model->setRawAttributes($build);
                    $model->save();
                });
            } catch (\Exception $e) {
                // 删除主题和帖子
                $thread->delete();
                $post->delete();
                throw new \Exception('asset_transform_error');
            }
        }

        return $thread;
    }

    /**
     * 上传图片
     *
     * @param $url
     * @throws \Exception
     */
    public function uploadImg($url)
    {
        $response = $this->client->request('get', $url);
        if ($response->getStatusCode() != 200) {
            throw new \Exception('获取图片失败');
        }

        // 图片二进制内容
        $img = $response->getBody()->getContents();

        /**
         * 上传图片 / 并生成模糊图
         */
        $fileName = Str::random(40) . '.png';
        $filePath = $this->upload->getPath();
        $this->upload->put($this->attachmentType, $img, $fileName, $filePath);

        /**
         * 判断是否开启 Cos
         */
        if (! $isRemote = $this->upload->isRemote()) {
            // 图片存入本地
            $complete = $filePath . $fileName;
            $this->filesystem->put($complete, $img);
            $absolutePath = $this->filesystem->path($complete);

            // 生成缩略图
            $thumbnail = (new ImageManager)->make($absolutePath);
            $thumbPath = Str::replaceLast($thumbnail->filename, $thumbnail->filename . '_thumb', $thumbnail->basePath());
            $blurPath = Str::replaceLast($thumbnail->filename, md5($thumbnail->filename) . '_blur', $thumbnail->basePath());

            $thumbnail->resize(Attachment::FIX_WIDTH, null, function ($constraint) {
                $constraint->aspectRatio();     // 保持纵横比
                $constraint->upsize();          // 避免文件变大
            })->save($thumbPath);

            // 生成模糊图
            $thumbnail->blur(80)->save($blurPath);
        }

        return $build = [
            'type' => $this->attachmentType,
            'is_remote' => $isRemote,
            'file_path' => $filePath,
        ];
    }
}
