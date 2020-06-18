<?php

/**
 * Discuz & Tencent Cloud
 * This is NOT a freeware, use is subject to license terms
 */

namespace App\SpecialChar;

use Discuz\SpecialChar\SpecialCharServer;
use HTMLPurifier;
use HTMLPurifier_Config;
use Discuz\Foundation\Application;

/**
 * Class SpecialChar
 * @package App\SpecialChar
 */
class SpecialChar implements SpecialCharServer
{
    /**
     * @var
     */
    public $app;

    /**
     * @var
     */
    public $config;

    /**
     * @var string 允许使用的HTML标签
     * @ps: 逗号分隔
     */
    public $html = '';

    /**
     * @var string 允许使用的CSS属性
     * @ps: 逗号分隔
     */
    public $css = '';

    /**
     * SpecialChar constructor.
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * 返回过滤后的字符串
     *
     * @param $string
     * @param null $config
     * @return mixed
     */
    public function purify($string, $config = null)
    {
        if ($config) {
            $this->$config();
        } else {
            $this->filtration();
        }

        // 使用配置生成过滤用的对象
        $purifier = new HTMLPurifier($this->config);

        // 过滤字符串
        return $purifier->purify($string);
    }

    /**
     * 设置过滤
     */
    public function filtration()
    {
        $this->setConfig();

        $this->config->set('HTML.Allowed', $this->html);

        $this->config->set('CSS.AllowedProperties', $this->css);

        // 清除空标签
        $this->config->set('AutoFormat.RemoveEmpty', true);

        $this->config->set('Cache.SerializerPath', storage_path('cache'));
    }

    public function textBlockConfig()
    {
        $this->setConfig();
        //设置白名单
        $this->config->set('HTML.Allowed', 'span[class],blockquote[class]');
        //替换成html实体码
        $this->config->set('Core.EscapeInvalidTags', true);

        $this->config->set('Cache.SerializerPath', storage_path('cache'));
    }



    public function setConfig()
    {
        // 生成配置对象
        $this->config = HTMLPurifier_Config::createDefault();

        // 设置字符集
        $this->config->set('Core.Encoding', 'UTF-8');
    }

    /**
     * 扩展设置
     *
     * @param $html
     */
    public function setHtmlLabel($html)
    {
        $this->html = $html;
    }

    /**
     * 扩展设置
     *
     * @param $css
     */
    public function setCSSLabel($css)
    {
        $this->css = $css;
    }
}
