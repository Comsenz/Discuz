<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\Traits;

use App\Exceptions\TranslatorException;
use App\Models\PostGood;

/**
 * 获取网址里的[商品信息]
 *
 * Trait PostGoodsTrait
 * @package App\Traits
 */
trait PostGoodsTrait
{
    /**
     * @var string 请求地址
     */
    protected $address;

    /**
     * @var array 商品枚举
     */
    protected $goodsType;

    /**
     * @var string 网页源代码
     */
    protected $html;

    /**
     * @var array (build goods info)
     */
    protected $goodsInfo = [
        'platform_id' => '',    // 平台商品ID
        'title' => '',
        'src' => '',
        'price' => 0.00,
    ];

    /**
     * @param $name
     * @param $arguments
     * @throws TranslatorException
     */
    public function __call($name, $arguments)
    {
        switch ($name) {
            case 'taobao':
                $this->taoBao();
                break;
            case 'tmall':
                $this->tMall();
                break;
            case 'jd':
            case 'm.jd':
                $this->JD();
                break;
            case 'yangkeduo':
                $this->pdd();
                break;
            case 'm.youzan':
                $this->youZan();
                break;
            case 'm.tb':
                $this->wirelessShare();
                break;
            default:
                throw new TranslatorException('post_goods_not_found_regex');
                break;
        }
    }

    /**
     * 阿里巴巴公有 特性/属性
     *
     * @param string $url
     */
    protected function getAlibabaGoodsId($url = '')
    {
        $address = empty($url) ? $this->address : $url;

        // get address platformId
        $addressRegex = '/id=(?<platform_id>[\d]+)&/';
        if (preg_match($addressRegex, $address, $matchAddress)) {
            $this->goodsInfo['platform_id'] = $matchAddress['platform_id'];
        }
    }

    protected function taoBao()
    {
        // get address platformId
        $this->getAlibabaGoodsId();

        // get title
        $titleRegex = '/<h3\s*class="tb-main-title"\s*data-title="(?<title>.+)">/i';
        if (preg_match($titleRegex, $this->html, $matchTitle)) {
            $this->goodsInfo['title'] = mb_convert_encoding($matchTitle['title'], 'UTF-8', 'GBK');
        }

        // get src
        $srcRegex = '/<img\s*id="J_ImgBooth"\s*src="(?<src>.+\.(jpeg|jpg|png))"/i';
        if (preg_match($srcRegex, $this->html, $matchSrc)) {
            $this->goodsInfo['src'] = trim($matchSrc['src'], '/');
        }

        // get price
        $priceRegex = '/<em\s*class="tb-rmb-num">(?<price>[\d\.]{1,12})<\/em>/i';
        if (preg_match($priceRegex, $this->html, $matchPrice)) {
            $this->goodsInfo['price'] = $matchPrice['price'];
        }
    }

    protected function tMall()
    {
        // get address platformId
        $this->getAlibabaGoodsId();

        // get title & src
        $tMallRegex = '/<img\s*id="J_ImgBooth"\s*alt="(?<title>.+)"\s*src="(?<src>.+\.(jpeg|jpg|png))"/i';
        if (preg_match($tMallRegex, $this->html, $matchContent)) {
            $this->goodsInfo['title'] = mb_convert_encoding($matchContent['title'], 'UTF-8', 'GBK');
            $this->goodsInfo['src'] = trim($matchContent['src'], '/');
        }

        // get price
        $tMallPrice = '/defaultItemPrice":"(?<price>[\d\.]{1,12})/i';
        if (preg_match($tMallPrice, $this->html, $matchPrice)) {
            $this->goodsInfo['price'] = $matchPrice['price'];
        }
    }

    protected function JD()
    {
        // get address platformId
        $idRegex = '/\/(?<platform_id>[\d]+)\.html/';
        if (preg_match($idRegex, $this->address, $matchAddress)) {
            $this->goodsInfo['platform_id'] = $matchAddress['platform_id'];
        }

        // get title
        $JDRegex = '/<div\s*class="fn_text_wrap"\s*id="itemName">(?<title>.+)<\/div>/i';
        if (preg_match($JDRegex, $this->html, $matchTitle)) {
            $this->goodsInfo['title'] = $matchTitle['title'];
        }

        // get src
        $srcRegex = '/<li\s*id="firstImgLi"\s*data-ul-child="child">\s*<img\s*id="firstImg"\s*onload=".*src="(?<src>.+\.[a-z]+)"/i';
        if (preg_match($srcRegex, $this->html, $matchSrc)) {
            $this->goodsInfo['src'] = trim($matchSrc['src'], '/');
        }

        // get price
        $priceRegex = '/<em>(?<price>\d+)<\/em>\s*<span\s*class="price_decimals">(?<price_point>.+)<\/span>/';
        if (preg_match($priceRegex, $this->html, $matchPrice)) {
            $this->goodsInfo['price'] = $matchPrice['price'] . $matchPrice['price_point'];
        }
    }

    protected function pdd()
    {
        // get address platformId
        $idRegex = '/goods_id=(?<platform_id>\d+)/';
        if (preg_match($idRegex, $this->address, $matchAddress)) {
            $this->goodsInfo['platform_id'] = $matchAddress['platform_id'];
        }
    }

    protected function youZan()
    {
        // get address platformId
        $idRegex = '/detail\/(?<platform_id>\w+)\?/i';
        if (preg_match($idRegex, $this->address, $matchAddress)) {
            $this->goodsInfo['platform_id'] = $matchAddress['platform_id'];
        } else {
            if (preg_match('/wscgoods\/detail\/(?<platform_id>\w+)\?/i', $this->html, $matchAddress)) {
                $this->goodsInfo['platform_id'] = $matchAddress['platform_id'];
            }
        }

        // get title & src
        $infoRegex = '/"cover"\s*:\s*"(?<src>.+\.(jpeg|jpg|png))"\s*,\s*"title"\s*:\s*"(?<title>.+)"\s*,\s*"desc"/i';
        if (preg_match($infoRegex, $this->html, $matchContent)) {
            $this->goodsInfo['title'] = $matchContent['title'];
            $this->goodsInfo['src'] = trim($matchContent['src'], '/');
        }

        // get price
        $priceRegex = '/"price"\s*:\s*(?<price>\d+),/';
        if (preg_match($priceRegex, $this->html, $matchPrice)) {
            $matchPrice['price'] = 38956;
            $this->goodsInfo['price'] = number_format($matchPrice['price'] / 100, 2);
        }
    }

    protected function wirelessShare()
    {
        // 匹配js跳转地址
        $regex = '/(?<address>(https|http):[\w\/\.\?\=\&\-]+)/i';
        if (preg_match($regex, $this->html, $match)) {
            $response = $this->httpClient->request('GET', $match['address'], [
                'allow_redirects' => [
                    'max' => 100,
                    'track_redirects' => true
                ]
            ]);

            // 获取最终的重定向真实域名地址
            $redirects = $response->getHeader('X-Guzzle-Redirect-History');
            if (!empty($redirects)) {
                $match['address'] = array_shift($redirects);
            }

            // 重新覆盖
            $this->html = $response->getBody()->getContents();
            $this->address = $match['address'];

            // 判断用 淘宝/天猫 提取方法
            $regex = '/(https|http):\/\/(?<url>[0-9a-z.]+)/i';
            if (preg_match($regex, $this->address, $match)) {
                PostGood::enumType(explode('.', $match['url']), function ($callback) {
                    $this->{$callback['value']}();
                });
            }
        }
    }
}
