<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Wechat;

use App\Api\Serializer\WechatAssetSerializer;
use Discuz\Api\Controller\AbstractResourceController;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Auth\Exception\PermissionDeniedException;
use Discuz\Contracts\Setting\SettingsRepository;
use EasyWeChat\Kernel\Exceptions\InvalidArgumentException;
use EasyWeChat\Kernel\Exceptions\InvalidConfigException;
use EasyWeChat\Kernel\Support\Collection;
use EasyWeChat\OfficialAccount\Application;
use Illuminate\Contracts\Validation\Factory;
use EasyWeChat\Factory as EasyWechatFactory;
use Illuminate\Support\Arr;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;
use EasyWeChat\Kernel\Messages\Article;

class OffIAccountAssetUploadController extends AbstractResourceController
{
    use AssertPermissionTrait;

    /**
     * @var string
     */
    public $serializer = WechatAssetSerializer::class;

    /**
     * @var Factory
     */
    protected $validator;

    /**
     * @var SettingsRepository
     */
    protected $settings;

    /**
     * @var Application
     */
    protected $easyWechat;

    /**
     * 允许上传的类型
     *
     * @var array
     */
    protected $allowTypes = [];

    /**
     * @param Factory $validator
     * @param SettingsRepository $settings
     * @param EasyWechatFactory $easyWechat
     */
    public function __construct(Factory $validator, SettingsRepository $settings, EasyWechatFactory $easyWechat)
    {
        $this->validator = $validator;
        $this->settings = $settings;

        $config = [
            'app_id' => $this->settings->get('offiaccount_app_id', 'wx_offiaccount'),
            'secret' => $this->settings->get('offiaccount_app_secret', 'wx_offiaccount'),
            'response_type' => 'array',
        ];

        $this->easyWechat = $easyWechat::officialAccount($config);
    }

    /**
     * @param ServerRequestInterface $request
     * @param Document $document
     * @return array|Collection|mixed|object|ResponseInterface|string
     * @throws InvalidArgumentException
     * @throws InvalidConfigException
     * @throws PermissionDeniedException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $this->assertAdmin($request->getAttribute('actor'));

        // 图片（image）、视频（video）、语音（voice）、图文（news）
        $body = $request->getParsedBody();
        $type = Arr::get($body, 'type', 'image');
        $file = Arr::get($request->getUploadedFiles(), 'file');

        // path name TODO 归类一个文件夹
        $path = storage_path('tmp/') . $file->getClientFilename();

        $file->moveTo($path);

        $result = [];
        switch ($type) {
            case 'image': // 图片（image）
                $result = $this->easyWechat->material->uploadImage($path);
                break;
            case 'video': // 视频（video）
                $videoTitle = Arr::get($body, 'video_title', '');
                $videoInfo = Arr::get($body, 'video_info', '');
                $result = $this->easyWechat->material->uploadVideo($path, $videoTitle, $videoInfo);
                break;
            case 'voice': // 语音（voice）
                $result = $this->easyWechat->material->uploadVoice($path);
                break;
            case 'news':  // 图文（news）
                // TODO 缺 validator 验证以下字段
                // 上传单篇图文
                $article = new Article([
                    'title' => Arr::get($body, 'news_title', ''),        // 标题
                    'thumb_media_id' => Arr::get($body, 'news_media_id', ''), // 封面图ID
                    'authod' =>  Arr::get($body, 'news_author', ''),    // 作者(微信文档起名错误)
                    'digest' =>  Arr::get($body, 'news_digest', ''),    // 图文消息摘要
                    'show_cover_pic' => Arr::get($body, 'news_show_cover_pic', 1), // 是否显示封面，0为false，即不显示，1为true，即显示
                    'content' => Arr::get($body, 'news_content', ''), // 内容
                    'content_source_url' => Arr::get($body, 'news_content', 1),
                    'need_open_comment' => Arr::get($body, 'news_news_content', 1), // 是否打开评论，0不打开，1打开
                    // 是否粉丝才可评论，0所有人可评论，1粉丝才可评论
                    'only_fans_can_comment' => Arr::get($body, 'news_only_fans_can_comment', 0),
                ]);
                $result = $this->easyWechat->material->uploadArticle($article);
                break;
            case 'lot_of_news':
                // 或者多篇图文
                // $result = $this->easyWechat->material->uploadArticle([$article, $article2, ...]);
                break;
            case 'thumbnail':   // 缩略图
                $result = $this->easyWechat->material->uploadThumb($path);
                break;
        }

        // remove
        unlink($path);

        return $result;
    }
}
