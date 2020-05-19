<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Api\Controller\Analysis;

use App\Api\Serializer\PostGoodsSerializer;
use App\Exceptions\TranslatorException;
use App\Models\PostGoods;
use App\Traits\PostGoodsTrait;
use Discuz\Api\Controller\AbstractResourceController;
use Discuz\Auth\AssertPermissionTrait;
use GuzzleHttp\Client;
use Illuminate\Support\Arr;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class ResourceAnalysisGoodsController extends AbstractResourceController
{
    use PostGoodsTrait;
    use AssertPermissionTrait;

    protected $httpClient;

    /**
     * {@inheritdoc}
     */
    public $serializer = PostGoodsSerializer::class;

    /**
     * {@inheritdoc}
     */
    public $optionalInclude = ['user', 'post'];

    /**
     * ResourceInviteController constructor.
     */
    public function __construct()
    {
        $config = [
            'timeout' => 30,
        ];

        $this->httpClient = new Client($config);
    }

    /**
     * {@inheritdoc}
     *
     * @param ServerRequestInterface $request
     * @param Document $document
     * @return array|mixed
     * @throws TranslatorException
     * @throws \Discuz\Auth\Exception\NotAuthenticatedException
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $actor = $request->getAttribute('actor');
        $this->assertRegistered($actor);

        $readyContent = Arr::get($request->getParsedBody(), 'data.attributes.address');

        // Filter Url
        $addressRegex = '/(?<address>(https|http):[\S.]+)/i';
        if (!preg_match($addressRegex, $readyContent, $matchAddress)) {
            throw new TranslatorException('post_goods_not_found_address');
        }
        $this->address = $matchAddress['address'];

        // Regular Expression Url
        $extractionUrlRegex = '/(https|http):\/\/(?<url>[0-9a-z.]+)/i';
        if (!preg_match($extractionUrlRegex, $this->address, $match)) {
            throw new TranslatorException('post_goods_not_found_regex');
        }

        $url = $match['url'];
        if (empty($url)) {
            throw new TranslatorException('post_goods_fail_url');
        }

        // Judge Enum
        if (!PostGoods::enumType(explode('.', $url), function ($callback) {
            $this->goodsType = $callback;
        })) {
            throw new TranslatorException('post_goods_not_found_enum');
        }

        /**
         * Send
         * @see https://guzzle-cn.readthedocs.io/zh_CN/latest/request-options.html#allow-redirects
         */
        $sendType = PostGoods::setBySending($this->address);
        if ($sendType == 'Guzzle') {
            $response = $this->httpClient->request('GET', $this->address, [
                'allow_redirects' => [
                    'max' => 100,
                    'track_redirects' => true
                ],
            ]);
            if ($response->getStatusCode() != 200) {
                throw new TranslatorException('post_goods_http_client_fail');
            }
            $this->html = $response->getBody()->getContents();
        } elseif ($sendType == 'File') {
            $this->html = file_get_contents($this->address);
        }

        /**
         * Get GoodsInfo
         * @see PostGoodsTrait
         */
        $this->{$this->goodsType['value']}();

        // Build
        $build = [
            'user_id' => $actor->id,
            'post_id' => 0,
            'platform_id' => $this->goodsInfo['platform_id'],
            'title' => $this->goodsInfo['title'],
            'image_path' => $this->goodsInfo['src'],
            'price' => $this->goodsInfo['price'],
            'type' => $this->goodsType['key'],
            'status' => 0,  // TODO 解析商品下架状态
            'ready_content' => $readyContent,
            'detail_content' => $this->address,
        ];

        // Created
        $goods = PostGoods::store(
            $build['user_id'],
            $build['post_id'],
            $build['platform_id'],
            $build['title'],
            $build['price'],
            $build['image_path'],
            $build['type'],
            $build['status'],
            $build['ready_content'],
            $build['detail_content']
        );

        $goods->save();

        return $goods;
    }
}
