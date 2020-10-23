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

use App\Models\Attachment;
use App\Repositories\ThreadRepository;
use App\Settings\ForumSettingField;
use Carbon\Carbon;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Auth\Exception\PermissionDeniedException;
use Discuz\Contracts\Setting\SettingsRepository;
use Discuz\Http\DiscuzResponseFactory;
use Discuz\Http\UrlGenerator;
use Discuz\SpecialChar\SpecialCharServer;
use Discuz\Wechat\EasyWechatTrait;
use EasyWeChat\Kernel\Messages\Article;
use GuzzleHttp\Client;
use Illuminate\Contracts\Filesystem\Factory as Filesystem;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * 微信公众号 - 帖子转公众号图文素材
 *
 * @package App\Api\Controller\Wechat
 */
class OffIAccountThreadsReprintController implements RequestHandlerInterface
{
    use AssertPermissionTrait;
    use EasyWechatTrait;

    /**
     * @var $easyWechat
     */
    protected $easyWechat;

    /**
     * @var SettingsRepository
     */
    protected $settings;

    /**
     * @var ThreadRepository
     */
    protected $thread;

    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * @var ForumSettingField
     */
    protected $forumField;

    /**
     * @var UrlGenerator
     */
    protected $url;

    /**
     * @var
     */
    protected $content;

    /**
     * @var SpecialCharServer
     */
    protected $specialChar;

    /**
     * @var Client
     */
    protected $client;

    /**
     * OffIAccountThreadsReprintController constructor.
     *
     * @param ThreadRepository $thread
     * @param Filesystem $filesystem
     * @param ForumSettingField $forumField
     * @param SettingsRepository $settings
     * @param UrlGenerator $url
     * @param SpecialCharServer $specialChar
     * @param Client $client
     */
    public function __construct(
        ThreadRepository $thread,
        Filesystem $filesystem,
        ForumSettingField $forumField,
        SettingsRepository $settings,
        UrlGenerator $url,
        SpecialCharServer $specialChar,
        Client $client
    ) {
        $this->thread = $thread;
        $this->filesystem = $filesystem;
        $this->forumField = $forumField;
        $this->client = $client;
        $this->settings = $settings;
        $this->url = $url;
        $this->specialChar = $specialChar;
        $this->easyWechat = $this->offiaccount();
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws PermissionDeniedException
     * @throws \Exception
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $this->assertAdmin($request->getAttribute('actor'));
        $threadId = Arr::get($request->getQueryParams(), 'id');

        // 查询主题内容
        $thread = $this->thread->findOrFail($threadId);
        // 获取内容
        $this->content = $thread->firstPost->formatContent();

        // 判断是否是视频贴
        $isImg = false;
        $isVideo = false;
        if ($thread->type == 2) {
            $isVideo = true;
            if (!empty($thread->threadVideo)) {
                $fileName = $thread->threadVideo->file_name;
                $extension = '.' . pathinfo($fileName, PATHINFO_EXTENSION);
                $fileName = str_replace($extension, '.png', $fileName);
                $url = $thread->threadVideo->cover_url;
                if (empty($url)) {
                    $isImg = true;
                }
            } else {
                $isImg = true;
            }
        } else {
            /**
             * filter code block
             */
            $this->filterCodeBlockFormatter();

            // 判断是否有图片，取第一张当做封面图
            if (!$thread->firstPost->images->isEmpty()) {
                $imageModel = $thread->firstPost->images->first();
                $this->processingPicture($imageModel, function ($arr) use (&$url, &$fileName) {
                    $url = $arr['url'];
                    $fileName = $arr['file_name'];
                });
                // 过滤添加其余图片
                if ($thread->firstPost->images->count() > 1) {
                    $this->filterImgFormatter($thread->firstPost->images);
                }
            } else {
                $isImg = true;
            }
        }

        // 如果缺失封面图，统一用站点Logo图
        if ($isImg) {
            // 默认图
            $url = $this->forumField->siteUrlSplicing($this->settings->get('header_logo'));
            if (empty($url)) {
                $url = $this->url->to('/storage/header_logo.png');
            }
            $fileName = 'header_logo.png';
        }

        $isVideo ? $field = ['url', 'media_id'] : $field = ['media_id'];

        /**
         * Upload
         * @var string $url
         * @var string $fileName
         */
        $resultImg = $this->uploadAssetImg($url, $fileName, $field);
        $assetMediaId = Arr::get($resultImg, 'media_id');

        if ($isVideo) {
            $assetUrl = Arr::get($resultImg, 'url');
            $this->videoFormatter($thread->id, $assetUrl);
            $title = $this->specialChar->purify($thread->getContentByType(50));
        } else {
            $title = $thread->getContentByType(50);
        }

        $build = [
            'title' => $title,                  // 标题
            'thumb_media_id' => $assetMediaId,  // 图文消息的封面图片素材id（必须是永久 media_ID）
            'show_cover' => 1,                  // 是否显示封面
            'content' => $this->content,        // 图文消息的具体内容，支持HTML标签，必须少于2万字符，小于1M，且此处会去除JS
        ];
        $article = new Article($build);
        $response = $this->easyWechat->material->uploadArticle($article);

        $build = [
            'title' => $title,
            'content' => $this->content,
            'media_id' => $response['media_id'], // 返回创建的素材ID
        ];

        return DiscuzResponseFactory::JsonResponse($build);
    }

    /**
     * 处理单个图片模型 - 上传图片素材前
     *
     * @param Attachment $model
     * @param array $callback
     */
    public function processingPicture(Attachment $model, $callback = [])
    {
        if ($model->is_remote) {
            $url = $this->settings->get('qcloud_cos_sign_url', 'qcloud', true)
                ? $this->filesystem->disk('attachment_cos')->temporaryUrl($model->full_path, Carbon::now()->addDay())
                : $this->filesystem->disk('attachment_cos')->url($model->full_path);
        } else {
            $url = $this->filesystem->disk('attachment')->url($model->full_path);
        }

        $callback([
            'url' => $url,
            'file_name' => $model->file_name,
        ]);
    }

    /**
     * 上传素材图片
     *
     * @param string $url 本地/cos 图片地址
     * @param string $fileName 原文件名
     * @param array $field 获取上传素材后微信字段
     * @return array
     * @throws \Exception
     */
    public function uploadAssetImg($url, $fileName, $field = ['media_id'])
    {
        $response = $this->client->request('get', $url);
        if ($response->getStatusCode() != 200) {
            throw new \Exception('获取封面图失败');
        }
        // 图片二进制内容
        $img = $response->getBody()->getContents();

        // 暂存图片
        $complete = 'tmp/' . $fileName;
        $this->filesystem->put($complete, $img);
        $absolutePath = $this->filesystem->path($complete);

        $result = $this->easyWechat->material->uploadImage($absolutePath);
        if (array_key_exists('errcode', $result)) {
            throw new \Exception($result['errmsg']);
        }

        // remove
        unlink($absolutePath);

        /**
         * media_id  url  item
         */
        return Arr::only($result, $field);
    }

    public function filterImgFormatter($images)
    {
        $str = '<p style="text-align: center;"><img class="rich_pages js_insertlocalimg" data-ratio="0.625" data-s="300,640" data-src="%s" data-type="png" data-w="1280" style=""/></p>';

        // 循环上传图片素材
        $images->map(function ($item, $key) use ($str) {
            // 剔除第一张，然后依次上传
            if ($key != 0) {
                $this->processingPicture($item, function ($arr) use (&$url, &$fileName) {
                    $url = $arr['url'];
                    $fileName = $arr['file_name'];
                });

                // Upload
                $assetUrl = Arr::get($this->uploadAssetImg($url, $fileName, ['url']), 'url');
                $string = sprintf($str, $assetUrl);

                // 拼接N张图
                $this->content = $this->content . $string;
            }
        });
    }

    public function filterCodeBlockFormatter()
    {
        // 公众号Markdown代码块格式
        $strUl = '<section class="code-snippet__fix code-snippet__js"><ul class="code-snippet__line-index code-snippet__js">';
        // $strUl .= '<li><li><li><li><li></ul>';
        $strCode = '<pre class="code-snippet__js"><code><span class="code-snippet_outer">%s</span></code></pre></section>';

        // 正则匹配 formatter 格式
        $regex = '/<pre><code>(?<code>[\s\S]*?)<\/code><\/pre>/ui';
        if (preg_match_all($regex, $this->content, $matchArr)) {
            for ($i = 0; $i < count($matchArr['code']); $i++) {
                // 统计内容中有 N 个 "\n"
                $nNum = substr_count($matchArr['code'][$i], "\n");

                // 循环拼接li
                $strLi = $strUl . '<li>'; // 初始化多一个
                for ($j = 0; $j < $nNum; $j++) {
                    $strLi .= '<li>';
                }
                $strLi .= '</ul>';

                $str = $strLi . $strCode;
                $str = sprintf($str, $matchArr['code'][$i]);
                $this->content = str_replace($matchArr[0][$i], $str, $this->content);
            }
        }

        return $this->content;
    }

    public function filterLinkFormatter()
    {
        // 正则匹配 formatter 格式
        $regex = '/<URL\surl="(?<url>.+)".+<\/s>(?<text>.+)<e>/u';
        if (preg_match($regex, $this->content, $matchContent)) {
            // 匹配转换公众号格式
            $replaceRegex = '/<URL.*?<\/URL>/u';
            $str = '<a target="_blank" href="%s" tab="outerlink" data-linktype="2">%s</a>';
            $str = sprintf($str, $matchContent['url'], $matchContent['text']);
            $this->content = preg_replace($replaceRegex, $str, $this->content);
        }

        return $this->content;
    }

    /**
     * 过滤视频帖子
     *
     * @param int $threadId 主题ID
     * @param string $assetUrl 视频封面图素材地址
     */
    public function videoFormatter($threadId, $assetUrl)
    {
        $toUrl = $this->url->to('/topic/index?id=' . $threadId);

        $str = '<p style="text-align: center;">
                    <a target="_blank" href="%s" tab="outerlink" data-linktype="2">
                        <img class="rich_pages js_insertlocalimg" data-ratio="0.625" data-s="300,640" data-src="%s" data-type="png" data-w="1280" style="width: 300px !important"/>
                        <p style="text-align: center">点击跳转视频原地址</p>
                    </a>
               </p>';

        $string = sprintf($str, $toUrl, $assetUrl);
        $this->content = $this->content . $string;
    }
}
