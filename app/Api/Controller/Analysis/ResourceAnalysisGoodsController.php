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

namespace App\Api\Controller\Analysis;

use App\Api\Serializer\PostGoodsSerializer;
use App\Exceptions\TranslatorException;
use App\Models\PostGoods;
use App\Traits\PostGoodsTrait;
use Discuz\Api\Controller\AbstractResourceController;
use Discuz\Auth\AssertPermissionTrait;
use GuzzleHttp\Client;
use Illuminate\Contracts\Routing\UrlGenerator;
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
     * @var UrlGenerator
     */
    protected $url;

    protected $allowDomain = [
        'taobao.com',
        'tmall.com',
        'detail.tmall.com',
        'jd.com',
        'm.jd.com',
        'yangkeduo.com',
        'youzan.com',
        'm.youzan.com',
        'tb.cn',
    ];

    /**
     * ResourceInviteController constructor.
     *
     * @param UrlGenerator $url
     */
    public function __construct(UrlGenerator $url)
    {
        $this->url = $url;

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

        /**
         * 查询数据库中是否存在
         */
        $postGoods = PostGoods::query();
        $postGoods->where('post_id', 0);
        $goods = $postGoods->where('ready_content', $readyContent)->first();
        if (!empty($goods)) {
            return $goods;
        }

        // Filter Url
        $addressRegex = '/(?<address>(https|http):[\S.]+)/i';
        if (!preg_match($addressRegex, $readyContent, $matchAddress)) {
            throw new TranslatorException('post_goods_not_found_address');
        }
        $this->address = $matchAddress['address'];

        // Validator Address
        $domainRegex = '/https:\/\/(([^:\/]*?)\.(?<url>.+?\.(cn|com)))/i';
        if (preg_match($domainRegex, $this->address, $domainUrl)) {
            if (! in_array($domainUrl['url'], $this->allowDomain)) {
                throw new TranslatorException('post_goods_does_not_resolve');
            }
        }

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
         * 如果是淘口令
         * （获取标题判断数据库是否存在该商品）
         */
        if ($this->goodsType['key'] == 5) {
            $titleRegex = '/【(?<title>.*)】/i';
            if (preg_match($titleRegex, $readyContent, $matchContent)) {
                $existGoods = PostGoods::query()
                    ->where('title', $matchContent['title'])
                    ->where('post_id', 0)
                    ->first();
                if (!empty($existGoods)) {
                    return $existGoods;
                }
            }
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

        /**
         * check GoodsInfo
         */
        $this->checkGoods();

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

    protected function checkGoods()
    {
        $this->goodsInfo['title'] ?: PostGoods::enumTypeName($this->goodsType['key'], '商品');

        switch ($this->goodsType['key']) {
            case 0: // 淘宝
            case 5: // 淘宝口令粘贴值
                $this->goodsInfo['src'] ?: $this->goodsInfo['src'] = $this->getDefaultIconUrl('taobao.svg');
                break;
            case 1: // 天猫
                $this->goodsInfo['src'] ?: $this->goodsInfo['src'] = $this->getDefaultIconUrl('tmall.svg');
                break;
            case 2: // 京东
            case 6: // 京东粘贴值H5域名
                $this->goodsInfo['src'] ?: $this->goodsInfo['src'] = $this->getDefaultIconUrl('jd.svg');
                break;
            case 3: // 拼多多H5
                $this->goodsInfo['src'] ?: $this->goodsInfo['src'] = $this->getDefaultIconUrl('pdd.svg');
                break;
            case 4: // 有赞
            case 7: // 有赞粘贴值
                $this->goodsInfo['src'] ?: $this->goodsInfo['src'] = $this->getDefaultIconUrl('youzan.svg');
                break;
        }
    }

    protected function getDefaultIconUrl($imgName)
    {
        return $this->url->to('/images/goods/' . $imgName);
    }
}
