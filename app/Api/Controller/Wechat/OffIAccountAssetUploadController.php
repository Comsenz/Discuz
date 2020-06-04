<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Wechat;

use App\Api\Serializer\WechatAssetSerializer;
use App\Validators\OffIAccountAssetUploadValidator;
use Discuz\Api\Controller\AbstractResourceController;
use Discuz\Auth\AssertPermissionTrait;
use Discuz\Auth\Exception\PermissionDeniedException;
use Discuz\Contracts\Setting\SettingsRepository;
use EasyWeChat\Kernel\Exceptions\InvalidArgumentException;
use EasyWeChat\Kernel\Exceptions\InvalidConfigException;
use EasyWeChat\Kernel\Support\Collection;
use EasyWeChat\OfficialAccount\Application;
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
     * @param OffIAccountAssetUploadValidator $validator
     * @param SettingsRepository $settings
     * @param EasyWechatFactory $easyWechat
     */
    public function __construct(OffIAccountAssetUploadValidator $validator, SettingsRepository $settings, EasyWechatFactory $easyWechat)
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
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $this->assertAdmin($request->getAttribute('actor'));

        // 图片（image）、视频（video）、语音（voice）、图文（news）
        $type = Arr::get($request->getQueryParams(), 'type', '');
        $body = $request->getParsedBody();

        $path = '';
        if ($isNews = $type != 'news') {
            $file = Arr::get($request->getUploadedFiles(), 'file');

            // path name
            $path = storage_path('tmp/') . $file->getClientFilename();

            $file->moveTo($path);
        }

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
                $news = json_decode(Arr::get($body, 'news', []), true);
                /**
                 * @see OffIAccountAssetUploadValidator
                 */
                $this->validator->valid($news);

                // 上传单篇图文
                $article = new Article($news);
                $result = $this->easyWechat->material->uploadArticle($article);
                break;
            case 'lot_of_news':
                // TODO 或者多篇图文
                // $result = $this->easyWechat->material->uploadArticle([$article, $article2, ...]);
                break;
            case 'thumbnail':   // 缩略图
                $result = $this->easyWechat->material->uploadThumb($path);
                break;
        }

        if ($isNews) {
            // remove
            unlink($path);
        }

        return $result;
    }
}
